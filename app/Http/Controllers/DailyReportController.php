<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\Inspection;
use App\Models\User;
use Carbon\Carbon;

class DailyReportController extends Controller
{
    public function index(Request $request)
    {
        $dateStr = $request->input('date', Carbon::today()->toDateString());
        $date = Carbon::parse($dateStr);

        $selectedBranch = $request->input('bank_branch');
        $selectedRole = $request->input('role');
        $selectedStatus = $request->input('status');
        $search = $request->input('search');

        // Bank Branches dropdown list (User with role 'Bank Branch')
        $bankBranches = User::whereHas('roles', function ($q) {
            $q->where('name', 'Bank Branch');
        })->orderBy('name')->pluck('name', 'id');

        // Available roles for filter
        $availableRoles = [
            'In-Charge' => 'In-Charge',
            'Surveyor' => 'Surveyor',
            'Reporter' => 'Reporter',
            'Checker' => 'Checker',
            'Delivery Person' => 'Delivery Person',
        ];

        // Available status / result options
        $availableStatuses = [
            'Positive' => 'Positive Result',
            'Negative' => 'Negative Result',
            'Canceled' => 'Canceled',
            'Hold' => 'On Hold',
            'Created' => 'Created Today',
            'Surveyed' => 'Surveyed Today',
            'Reported' => 'Reported Today',
            'Checked' => 'Checked Today',
            'Delivered' => 'Delivered Today',
        ];

        // 1. Overall Work Done Today - Base KPI counts (unfiltered or filtered by branch)
        $kpiQuery = function () use ($date, $selectedBranch) {
            $q = Work::query();
            if ($selectedBranch) {
                $q->where('bank_branch', $selectedBranch);
            }
            return $q;
        };

        $createdCount = $kpiQuery()->whereDate('created_at', $date)->count();

        $surveyedCount = $kpiQuery()->whereHas('inspection', function ($q) use ($date) {
            $q->whereDate('created_at', $date);
        })->count();

        $reportedCount = $kpiQuery()->whereDate('reporting_ended_at', $date)->count();
        $checkedCount = $kpiQuery()->whereDate('checking_ended_at', $date)->count();

        $deliveredCount = $kpiQuery()->where('delivery_status', 'Delivery Done')
            ->whereDate('updated_at', $date)
            ->count();

        $canceledCount = $kpiQuery()->where('result', 'Canceled')
            ->whereDate('updated_at', $date)
            ->count();

        $positiveCount = $kpiQuery()->where('result', 'Positive')
            ->whereDate('updated_at', $date)
            ->count();

        $negativeCount = $kpiQuery()->where('result', 'Negative')
            ->whereDate('updated_at', $date)
            ->count();

        $holdCount = $kpiQuery()->where('is_hold', 1)
            ->where(function ($q) use ($date) {
                $q->whereDate('updated_at', $date)
                  ->orWhereDate('created_at', $date);
            })->count();

        // 2. Fetch all works touched on this date with all related entities
        $baseWorksQuery = Work::where(function ($query) use ($date) {
            $query->whereDate('created_at', $date)
                ->orWhereHas('inspection', function ($q) use ($date) {
                    $q->whereDate('created_at', $date);
                })
                ->orWhereDate('reporting_ended_at', $date)
                ->orWhereDate('checking_ended_at', $date)
                ->orWhere(function ($q) use ($date) {
                    $q->whereDate('updated_at', $date)
                        ->where(function ($sub) {
                            $sub->where('delivery_status', 'Delivery Done')
                                ->orWhereNotNull('result');
                        });
                });
        })->with(['creator', 'surveyor', 'reporter', 'checker', 'deliveryPerson', 'bankBranch', 'inspection.creator']);

        if ($selectedBranch) {
            $baseWorksQuery->where('bank_branch', $selectedBranch);
        }

        if ($search) {
            $baseWorksQuery->where(function ($q) use ($search) {
                $q->where('custom_id', 'like', "%{$search}%")
                  ->orWhere('name_of_applicant', 'like', "%{$search}%")
                  ->orWhere('bank_name', 'like', "%{$search}%")
                  ->orWhere('project_name', 'like', "%{$search}%")
                  ->orWhere('remarks', 'like', "%{$search}%");
            });
        }

        $allTouchedWorks = $baseWorksQuery->get();

        // Apply Status Filter in collection if specified
        if ($selectedStatus) {
            $allTouchedWorks = $allTouchedWorks->filter(function ($work) use ($selectedStatus, $date) {
                switch ($selectedStatus) {
                    case 'Positive': return $work->result === 'Positive';
                    case 'Negative': return $work->result === 'Negative';
                    case 'Canceled': return $work->result === 'Canceled';
                    case 'Hold': return (bool)$work->is_hold;
                    case 'Created': return $work->created_at && $work->created_at->isSameDay($date);
                    case 'Surveyed': return $work->inspection && $work->inspection->created_at && $work->inspection->created_at->isSameDay($date);
                    case 'Reported': return $work->reporting_ended_at && $work->reporting_ended_at->isSameDay($date);
                    case 'Checked': return $work->checking_ended_at && $work->checking_ended_at->isSameDay($date);
                    case 'Delivered': return $work->delivery_status === 'Delivery Done' && $work->updated_at && $work->updated_at->isSameDay($date);
                    default: return true;
                }
            })->values();
        }

        // Apply Role Filter in collection if specified
        if ($selectedRole) {
            $allTouchedWorks = $allTouchedWorks->filter(function ($work) use ($selectedRole, $date) {
                switch ($selectedRole) {
                    case 'In-Charge':
                        return $work->created_at && $work->created_at->isSameDay($date) && $work->created_by;
                    case 'Surveyor':
                        return $work->inspection && $work->inspection->created_at && $work->inspection->created_at->isSameDay($date) && ($work->inspection->created_by || $work->assignee_surveyor);
                    case 'Reporter':
                        return $work->reporting_ended_at && $work->reporting_ended_at->isSameDay($date) && $work->assignee_reporter;
                    case 'Checker':
                        return $work->checking_ended_at && $work->checking_ended_at->isSameDay($date) && $work->assignee_checker;
                    case 'Delivery Person':
                        return $work->delivery_status === 'Delivery Done' && $work->updated_at && $work->updated_at->isSameDay($date) && $work->assignee_delivery;
                    default:
                        return true;
                }
            })->values();
        }

        $totalActiveWorks = $allTouchedWorks->count();

        // 3. Operational Efficiencies (Average durations)
        $avgReporting = Work::whereDate('reporting_ended_at', $date)
            ->whereNotNull('reporting_started_at')
            ->whereNotNull('reporting_ended_at')
            ->when($selectedBranch, fn($q) => $q->where('bank_branch', $selectedBranch))
            ->get()
            ->avg('reporting_duration_minutes') ?? 0;

        $avgChecking = Work::whereDate('checking_ended_at', $date)
            ->whereNotNull('checking_started_at')
            ->whereNotNull('checking_ended_at')
            ->when($selectedBranch, fn($q) => $q->where('bank_branch', $selectedBranch))
            ->get()
            ->avg('checking_duration_minutes') ?? 0;

        // 4. BANK BRANCH SEGMENTATION ("which user did which work based on role and status segmented by bank branch")
        $branchSegmentation = [];
        foreach ($allTouchedWorks as $work) {
            $branchId = $work->bank_branch ?? 0;
            $branchName = $work->bankBranch ? $work->bankBranch->name : ($work->bank_name ? $work->bank_name . ' (Direct)' : 'Unassigned Branch');
            $bankName = $work->bank_name ?? 'N/A';

            if (!isset($branchSegmentation[$branchId])) {
                $branchSegmentation[$branchId] = [
                    'branch_id' => $branchId,
                    'branch_name' => $branchName,
                    'bank_name' => $bankName,
                    'total_works' => 0,
                    'created' => 0,
                    'surveyed' => 0,
                    'reported' => 0,
                    'checked' => 0,
                    'delivered' => 0,
                    'positive' => 0,
                    'negative' => 0,
                    'canceled' => 0,
                    'hold' => 0,
                    'users_by_role' => [
                        'In-Charge' => [],
                        'Surveyor' => [],
                        'Reporter' => [],
                        'Checker' => [],
                        'Delivery Person' => [],
                    ],
                    'works' => [],
                ];
            }

            $branchSegmentation[$branchId]['total_works']++;

            $isCreatedToday = $work->created_at && $work->created_at->isSameDay($date);
            $isSurveyedToday = $work->inspection && $work->inspection->created_at && $work->inspection->created_at->isSameDay($date);
            $isReportedToday = $work->reporting_ended_at && $work->reporting_ended_at->isSameDay($date);
            $isCheckedToday = $work->checking_ended_at && $work->checking_ended_at->isSameDay($date);
            $isDeliveredToday = $work->delivery_status === 'Delivery Done' && $work->updated_at && $work->updated_at->isSameDay($date);

            if ($isCreatedToday) $branchSegmentation[$branchId]['created']++;
            if ($isSurveyedToday) $branchSegmentation[$branchId]['surveyed']++;
            if ($isReportedToday) $branchSegmentation[$branchId]['reported']++;
            if ($isCheckedToday) $branchSegmentation[$branchId]['checked']++;
            if ($isDeliveredToday) $branchSegmentation[$branchId]['delivered']++;

            if ($work->result === 'Positive') $branchSegmentation[$branchId]['positive']++;
            if ($work->result === 'Negative') $branchSegmentation[$branchId]['negative']++;
            if ($work->result === 'Canceled') $branchSegmentation[$branchId]['canceled']++;
            if ($work->is_hold) $branchSegmentation[$branchId]['hold']++;

            // Associate users by role for this branch
            if ($isCreatedToday && $work->creator) {
                $uId = $work->creator->id;
                $branchSegmentation[$branchId]['users_by_role']['In-Charge'][$uId] = [
                    'name' => $work->creator->name,
                    'count' => ($branchSegmentation[$branchId]['users_by_role']['In-Charge'][$uId]['count'] ?? 0) + 1,
                ];
            }
            if ($isSurveyedToday) {
                $surveyorUser = ($work->inspection && $work->inspection->creator) ? $work->inspection->creator : $work->surveyor;
                if ($surveyorUser) {
                    $uId = $surveyorUser->id;
                    $branchSegmentation[$branchId]['users_by_role']['Surveyor'][$uId] = [
                        'name' => $surveyorUser->name,
                        'count' => ($branchSegmentation[$branchId]['users_by_role']['Surveyor'][$uId]['count'] ?? 0) + 1,
                    ];
                }
            }
            if ($isReportedToday && $work->reporter) {
                $uId = $work->reporter->id;
                $branchSegmentation[$branchId]['users_by_role']['Reporter'][$uId] = [
                    'name' => $work->reporter->name,
                    'count' => ($branchSegmentation[$branchId]['users_by_role']['Reporter'][$uId]['count'] ?? 0) + 1,
                ];
            }
            if ($isCheckedToday && $work->checker) {
                $uId = $work->checker->id;
                $branchSegmentation[$branchId]['users_by_role']['Checker'][$uId] = [
                    'name' => $work->checker->name,
                    'count' => ($branchSegmentation[$branchId]['users_by_role']['Checker'][$uId]['count'] ?? 0) + 1,
                ];
            }
            if ($isDeliveredToday && $work->deliveryPerson) {
                $uId = $work->deliveryPerson->id;
                $branchSegmentation[$branchId]['users_by_role']['Delivery Person'][$uId] = [
                    'name' => $work->deliveryPerson->name,
                    'count' => ($branchSegmentation[$branchId]['users_by_role']['Delivery Person'][$uId]['count'] ?? 0) + 1,
                ];
            }

            $branchSegmentation[$branchId]['works'][] = [
                'id' => $work->id,
                'custom_id' => $work->custom_id,
                'applicant_name' => $work->name_of_applicant,
                'project_name' => $work->project_name,
                'status' => $work->status,
                'result' => $work->result,
                'delivery_status' => $work->delivery_status,
                'is_hold' => (bool)$work->is_hold,
                'remarks' => $work->remarks,
                'is_created_today' => $isCreatedToday,
                'is_surveyed_today' => $isSurveyedToday,
                'is_reported_today' => $isReportedToday,
                'is_checked_today' => $isCheckedToday,
                'is_delivered_today' => $isDeliveredToday,
                'incharge_name' => $work->creator->name ?? null,
                'surveyor_name' => ($work->inspection && $work->inspection->creator) ? $work->inspection->creator->name : ($work->surveyor->name ?? null),
                'survey_time' => ($work->inspection && $work->inspection->created_at) ? $work->inspection->created_at->format('h:i A') : null,
                'reporter_name' => $work->reporter->name ?? null,
                'reporting_duration' => $work->reporting_duration_minutes,
                'checker_name' => $work->checker->name ?? null,
                'checking_duration' => $work->checking_duration_minutes,
                'delivery_name' => $work->deliveryPerson->name ?? null,
            ];
        }

        // Sort branches by total works descending
        uasort($branchSegmentation, fn($a, $b) => $b['total_works'] <=> $a['total_works']);

        // 5. ROLE & USER WORK MATRIX ("which user did which work based on role and status")
        $roleWorkMatrix = [
            'In-Charge' => [],
            'Surveyor' => [],
            'Reporter' => [],
            'Checker' => [],
            'Delivery Person' => [],
        ];

        // Consolidated user activity (backward-compatible with existing test & view expectations)
        $userActivity = [];

        foreach ($allTouchedWorks as $work) {
            $branchName = $work->bankBranch ? $work->bankBranch->name : ($work->bank_name ? $work->bank_name : 'Unassigned Branch');

            // 1) In-Charge (Created)
            if ($work->created_at && $work->created_at->isSameDay($date) && $work->creator) {
                $u = $work->creator;
                if (!isset($roleWorkMatrix['In-Charge'][$u->id])) {
                    $roleWorkMatrix['In-Charge'][$u->id] = [
                        'user_id' => $u->id,
                        'name' => $u->name,
                        'email' => $u->email,
                        'works_count' => 0,
                        'branches' => [],
                        'works' => [],
                    ];
                }
                $roleWorkMatrix['In-Charge'][$u->id]['works_count']++;
                $roleWorkMatrix['In-Charge'][$u->id]['branches'][$branchName] = ($roleWorkMatrix['In-Charge'][$u->id]['branches'][$branchName] ?? 0) + 1;
                $roleWorkMatrix['In-Charge'][$u->id]['works'][] = [
                    'work_id' => $work->id,
                    'custom_id' => $work->custom_id,
                    'applicant' => $work->name_of_applicant,
                    'branch' => $branchName,
                    'status' => $work->status,
                    'result' => $work->result,
                    'time' => $work->created_at->format('h:i A'),
                ];

                $userActivity[$u->id]['name'] = $u->name;
                $userActivity[$u->id]['role'] = 'In-Charge';
                $userActivity[$u->id]['created'] = ($userActivity[$u->id]['created'] ?? 0) + 1;
            }

            // 2) Surveyor
            $isSurveyedToday = $work->inspection && $work->inspection->created_at && $work->inspection->created_at->isSameDay($date);
            $surveyorUser = ($work->inspection && $work->inspection->creator) ? $work->inspection->creator : $work->surveyor;
            if ($isSurveyedToday && $surveyorUser) {
                $u = $surveyorUser;
                if (!isset($roleWorkMatrix['Surveyor'][$u->id])) {
                    $roleWorkMatrix['Surveyor'][$u->id] = [
                        'user_id' => $u->id,
                        'name' => $u->name,
                        'email' => $u->email,
                        'works_count' => 0,
                        'branches' => [],
                        'works' => [],
                    ];
                }
                $roleWorkMatrix['Surveyor'][$u->id]['works_count']++;
                $roleWorkMatrix['Surveyor'][$u->id]['branches'][$branchName] = ($roleWorkMatrix['Surveyor'][$u->id]['branches'][$branchName] ?? 0) + 1;
                $roleWorkMatrix['Surveyor'][$u->id]['works'][] = [
                    'work_id' => $work->id,
                    'custom_id' => $work->custom_id,
                    'applicant' => $work->name_of_applicant,
                    'branch' => $branchName,
                    'status' => $work->status,
                    'result' => $work->result,
                    'time' => $work->inspection->created_at->format('h:i A'),
                ];

                $userActivity[$u->id]['name'] = $u->name;
                if (!isset($userActivity[$u->id]['role'])) $userActivity[$u->id]['role'] = 'Surveyor';
                $userActivity[$u->id]['surveyed'] = ($userActivity[$u->id]['surveyed'] ?? 0) + 1;
            }

            // 3) Reporter
            if ($work->reporting_ended_at && $work->reporting_ended_at->isSameDay($date) && $work->reporter) {
                $u = $work->reporter;
                if (!isset($roleWorkMatrix['Reporter'][$u->id])) {
                    $roleWorkMatrix['Reporter'][$u->id] = [
                        'user_id' => $u->id,
                        'name' => $u->name,
                        'email' => $u->email,
                        'works_count' => 0,
                        'durations' => [],
                        'branches' => [],
                        'works' => [],
                    ];
                }
                $roleWorkMatrix['Reporter'][$u->id]['works_count']++;
                if ($work->reporting_duration_minutes !== null) {
                    $roleWorkMatrix['Reporter'][$u->id]['durations'][] = $work->reporting_duration_minutes;
                }
                $roleWorkMatrix['Reporter'][$u->id]['branches'][$branchName] = ($roleWorkMatrix['Reporter'][$u->id]['branches'][$branchName] ?? 0) + 1;
                $roleWorkMatrix['Reporter'][$u->id]['works'][] = [
                    'work_id' => $work->id,
                    'custom_id' => $work->custom_id,
                    'applicant' => $work->name_of_applicant,
                    'branch' => $branchName,
                    'status' => $work->status,
                    'result' => $work->result,
                    'duration' => $work->reporting_duration_minutes,
                    'time' => $work->reporting_ended_at->format('h:i A'),
                ];

                $userActivity[$u->id]['name'] = $u->name;
                if (!isset($userActivity[$u->id]['role'])) $userActivity[$u->id]['role'] = 'Reporter';
                $userActivity[$u->id]['reported'] = ($userActivity[$u->id]['reported'] ?? 0) + 1;
            }

            // 4) Checker
            if ($work->checking_ended_at && $work->checking_ended_at->isSameDay($date) && $work->checker) {
                $u = $work->checker;
                if (!isset($roleWorkMatrix['Checker'][$u->id])) {
                    $roleWorkMatrix['Checker'][$u->id] = [
                        'user_id' => $u->id,
                        'name' => $u->name,
                        'email' => $u->email,
                        'works_count' => 0,
                        'durations' => [],
                        'branches' => [],
                        'works' => [],
                    ];
                }
                $roleWorkMatrix['Checker'][$u->id]['works_count']++;
                if ($work->checking_duration_minutes !== null) {
                    $roleWorkMatrix['Checker'][$u->id]['durations'][] = $work->checking_duration_minutes;
                }
                $roleWorkMatrix['Checker'][$u->id]['branches'][$branchName] = ($roleWorkMatrix['Checker'][$u->id]['branches'][$branchName] ?? 0) + 1;
                $roleWorkMatrix['Checker'][$u->id]['works'][] = [
                    'work_id' => $work->id,
                    'custom_id' => $work->custom_id,
                    'applicant' => $work->name_of_applicant,
                    'branch' => $branchName,
                    'status' => $work->status,
                    'result' => $work->result,
                    'duration' => $work->checking_duration_minutes,
                    'time' => $work->checking_ended_at->format('h:i A'),
                ];

                $userActivity[$u->id]['name'] = $u->name;
                if (!isset($userActivity[$u->id]['role'])) $userActivity[$u->id]['role'] = 'Checker';
                $userActivity[$u->id]['checked'] = ($userActivity[$u->id]['checked'] ?? 0) + 1;
            }

            // 5) Delivery Person
            $isDeliveredToday = $work->delivery_status === 'Delivery Done' && $work->updated_at && $work->updated_at->isSameDay($date);
            if ($isDeliveredToday && $work->deliveryPerson) {
                $u = $work->deliveryPerson;
                if (!isset($roleWorkMatrix['Delivery Person'][$u->id])) {
                    $roleWorkMatrix['Delivery Person'][$u->id] = [
                        'user_id' => $u->id,
                        'name' => $u->name,
                        'email' => $u->email,
                        'works_count' => 0,
                        'branches' => [],
                        'works' => [],
                    ];
                }
                $roleWorkMatrix['Delivery Person'][$u->id]['works_count']++;
                $roleWorkMatrix['Delivery Person'][$u->id]['branches'][$branchName] = ($roleWorkMatrix['Delivery Person'][$u->id]['branches'][$branchName] ?? 0) + 1;
                $roleWorkMatrix['Delivery Person'][$u->id]['works'][] = [
                    'work_id' => $work->id,
                    'custom_id' => $work->custom_id,
                    'applicant' => $work->name_of_applicant,
                    'branch' => $branchName,
                    'status' => $work->status,
                    'result' => $work->result,
                    'time' => $work->updated_at->format('h:i A'),
                ];

                $userActivity[$u->id]['name'] = $u->name;
                if (!isset($userActivity[$u->id]['role'])) $userActivity[$u->id]['role'] = 'Delivery Person';
                $userActivity[$u->id]['delivered'] = ($userActivity[$u->id]['delivered'] ?? 0) + 1;
            }
        }

        // Calculate average timings for reporting & checking per user
        foreach ($userActivity as $userId => &$activity) {
            if (isset($activity['reported'])) {
                $avgReportingUser = Work::whereDate('reporting_ended_at', $date)
                    ->where('assignee_reporter', $userId)
                    ->whereNotNull('reporting_started_at')
                    ->whereNotNull('reporting_ended_at')
                    ->get()
                    ->avg('reporting_duration_minutes') ?? 0;
                $activity['avg_reporting_time'] = round($avgReportingUser, 1);
            }
            if (isset($activity['checked'])) {
                $avgCheckingUser = Work::whereDate('checking_ended_at', $date)
                    ->where('assignee_checker', $userId)
                    ->whereNotNull('checking_started_at')
                    ->whereNotNull('checking_ended_at')
                    ->get()
                    ->avg('checking_duration_minutes') ?? 0;
                $activity['avg_checking_time'] = round($avgCheckingUser, 1);
            }
        }
        unset($activity);

        // Add avg duration to roleWorkMatrix
        foreach ($roleWorkMatrix['Reporter'] as &$item) {
            $item['avg_duration'] = count($item['durations']) ? round(array_sum($item['durations']) / count($item['durations']), 1) : 0;
        }
        unset($item);

        foreach ($roleWorkMatrix['Checker'] as &$item) {
            $item['avg_duration'] = count($item['durations']) ? round(array_sum($item['durations']) / count($item['durations']), 1) : 0;
        }
        unset($item);

        $activeStaffCount = count($userActivity);
        $activeBranchCount = count($branchSegmentation);

        $detailedWorks = $allTouchedWorks;

        // 6. CSV Export Action
        if ($request->input('action') === 'export') {
            return $this->exportCsv(
                $dateStr,
                $createdCount,
                $surveyedCount,
                $reportedCount,
                $checkedCount,
                $deliveredCount,
                $canceledCount,
                $positiveCount,
                $negativeCount,
                $userActivity,
                $branchSegmentation,
                $roleWorkMatrix,
                $detailedWorks
            );
        }

        return view('works.daily_report', compact(
            'dateStr',
            'bankBranches',
            'availableRoles',
            'availableStatuses',
            'selectedBranch',
            'selectedRole',
            'selectedStatus',
            'search',
            'totalActiveWorks',
            'createdCount',
            'surveyedCount',
            'reportedCount',
            'checkedCount',
            'deliveredCount',
            'canceledCount',
            'positiveCount',
            'negativeCount',
            'holdCount',
            'avgReporting',
            'avgChecking',
            'activeStaffCount',
            'activeBranchCount',
            'branchSegmentation',
            'roleWorkMatrix',
            'userActivity',
            'detailedWorks'
        ));
    }

    private function exportCsv(
        $dateStr,
        $createdCount,
        $surveyedCount,
        $reportedCount,
        $checkedCount,
        $deliveredCount,
        $canceledCount,
        $positiveCount,
        $negativeCount,
        $userActivity,
        $branchSegmentation,
        $roleWorkMatrix,
        $detailedWorks
    ) {
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=daily_report_{$dateStr}.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use (
            $dateStr,
            $createdCount,
            $surveyedCount,
            $reportedCount,
            $checkedCount,
            $deliveredCount,
            $canceledCount,
            $positiveCount,
            $negativeCount,
            $userActivity,
            $branchSegmentation,
            $roleWorkMatrix,
            $detailedWorks
        ) {
            $file = fopen('php://output', 'w');

            // 1. Title
            fputcsv($file, ["DAILY REPORT FOR {$dateStr}"]);
            fputcsv($file, []);

            // 2. Executive Summary
            fputcsv($file, ["EXECUTIVE OVERVIEW METRICS (TODAY)"]);
            fputcsv($file, ["Metric", "Count"]);
            fputcsv($file, ["Total Files Active Today", count($detailedWorks)]);
            fputcsv($file, ["New Files Created Today", $createdCount]);
            fputcsv($file, ["Field Surveys Completed", $surveyedCount]);
            fputcsv($file, ["Reports Drafted", $reportedCount]);
            fputcsv($file, ["Quality Checks Completed", $checkedCount]);
            fputcsv($file, ["Deliveries Completed", $deliveredCount]);
            fputcsv($file, ["Positive Results", $positiveCount]);
            fputcsv($file, ["Negative Results", $negativeCount]);
            fputcsv($file, ["Canceled Works", $canceledCount]);
            fputcsv($file, []);

            // 3. Bank Branch Segmentation Summary
            fputcsv($file, ["BANK BRANCH SEGMENTATION SUMMARY"]);
            fputcsv($file, [
                "Bank Name",
                "Branch Name",
                "Total Works",
                "Created",
                "Surveyed",
                "Reported",
                "Checked",
                "Delivered",
                "Positive",
                "Negative",
                "Involved Staff by Role"
            ]);
            foreach ($branchSegmentation as $branch) {
                $involvedStaff = [];
                foreach ($branch['users_by_role'] as $role => $users) {
                    if (!empty($users)) {
                        $userNames = array_map(fn($u) => "{$u['name']} ({$u['count']})", $users);
                        $involvedStaff[] = "{$role}: " . implode(', ', $userNames);
                    }
                }
                fputcsv($file, [
                    $branch['bank_name'],
                    $branch['branch_name'],
                    $branch['total_works'],
                    $branch['created'],
                    $branch['surveyed'],
                    $branch['reported'],
                    $branch['checked'],
                    $branch['delivered'],
                    $branch['positive'],
                    $branch['negative'],
                    implode(' | ', $involvedStaff),
                ]);
            }
            fputcsv($file, []);

            // 4. Staff Performance by Role
            fputcsv($file, ["STAFF PERFORMANCE BY ROLE"]);
            fputcsv($file, ["Role", "Staff Name", "Works Count", "Avg Duration (min)", "Branches Served"]);
            foreach ($roleWorkMatrix as $roleName => $users) {
                foreach ($users as $userId => $item) {
                    $branchList = [];
                    foreach ($item['branches'] as $bName => $bCount) {
                        $branchList[] = "{$bName} ({$bCount})";
                    }
                    fputcsv($file, [
                        $roleName,
                        $item['name'],
                        $item['works_count'],
                        $item['avg_duration'] ?? '-',
                        implode(', ', $branchList),
                    ]);
                }
            }
            fputcsv($file, []);

            // 5. Staff Leaderboard Summary
            fputcsv($file, ["STAFF SUMMARY LEADERBOARD"]);
            fputcsv($file, ["User Name", "Primary Role", "Created", "Surveyed", "Reported", "Avg Report (min)", "Checked", "Avg Check (min)", "Delivered"]);
            foreach ($userActivity as $userId => $activity) {
                fputcsv($file, [
                    $activity['name'],
                    $activity['role'] ?? '-',
                    $activity['created'] ?? 0,
                    $activity['surveyed'] ?? 0,
                    $activity['reported'] ?? 0,
                    $activity['avg_reporting_time'] ?? '-',
                    $activity['checked'] ?? 0,
                    $activity['avg_checking_time'] ?? '-',
                    $activity['delivered'] ?? 0,
                ]);
            }
            fputcsv($file, []);

            // 6. Detailed Work Log
            fputcsv($file, ["DETAILED WORK LOG"]);
            fputcsv($file, [
                "Work ID",
                "Applicant",
                "Bank Name",
                "Bank Branch",
                "In-Charge",
                "Surveyor",
                "Surveyed At",
                "Reporter",
                "Report Duration (min)",
                "Checker",
                "Check Duration (min)",
                "Delivery Person",
                "Delivery Status",
                "Status",
                "Result",
                "Remarks"
            ]);
            foreach ($detailedWorks as $work) {
                fputcsv($file, [
                    $work->custom_id,
                    $work->name_of_applicant,
                    $work->bank_name ?? '-',
                    $work->bankBranch->name ?? ($work->bank_name ? $work->bank_name . ' (Direct)' : '-'),
                    $work->creator->name ?? '-',
                    ($work->inspection && $work->inspection->creator) ? $work->inspection->creator->name : ($work->surveyor->name ?? '-'),
                    $work->inspection ? $work->inspection->created_at->format('h:i A') : '-',
                    $work->reporter->name ?? '-',
                    $work->reporting_duration_minutes ?? '-',
                    $work->checker->name ?? '-',
                    $work->checking_duration_minutes ?? '-',
                    $work->deliveryPerson->name ?? '-',
                    $work->delivery_status,
                    $work->status,
                    $work->result ?? '-',
                    $work->remarks ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
