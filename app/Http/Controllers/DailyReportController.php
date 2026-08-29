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

        // 1. KPI Counts
        $createdCount = Work::whereDate('created_at', $date)->count();
        
        $surveyedCount = Work::whereHas('inspection', function ($q) use ($date) {
            $q->whereDate('created_at', $date);
        })->count();

        $reportedCount = Work::whereDate('reporting_ended_at', $date)->count();
        $checkedCount = Work::whereDate('checking_ended_at', $date)->count();
        
        $deliveredCount = Work::where('delivery_status', 'Delivery Done')
            ->whereDate('updated_at', $date)
            ->count();

        $canceledCount = Work::where('result', 'Canceled')
            ->whereDate('updated_at', $date)
            ->count();

        $positiveCount = Work::where('result', 'Positive')
            ->whereDate('updated_at', $date)
            ->count();

        $negativeCount = Work::where('result', 'Negative')
            ->whereDate('updated_at', $date)
            ->count();

        // 2. User Leaderboard data
        $createdStats = Work::whereDate('created_at', $date)
            ->selectRaw('created_by, count(*) as count')
            ->groupBy('created_by')
            ->with('creator')
            ->get();

        $surveyStats = Inspection::whereDate('created_at', $date)
            ->selectRaw('created_by, count(*) as count')
            ->groupBy('created_by')
            ->with('creator')
            ->get();

        $reportStats = Work::whereDate('reporting_ended_at', $date)
            ->selectRaw('assignee_reporter, count(*) as count')
            ->groupBy('assignee_reporter')
            ->with('reporter')
            ->get();

        $checkStats = Work::whereDate('checking_ended_at', $date)
            ->selectRaw('assignee_checker, count(*) as count')
            ->groupBy('assignee_checker')
            ->with('checker')
            ->get();

        $deliveryStats = Work::where('delivery_status', 'Delivery Done')
            ->whereDate('updated_at', $date)
            ->selectRaw('assignee_delivery, count(*) as count')
            ->groupBy('assignee_delivery')
            ->with('deliveryPerson')
            ->get();

        $userActivity = [];

        foreach ($createdStats as $stat) {
            if ($stat->created_by) {
                $userActivity[$stat->created_by]['name'] = $stat->creator->name ?? 'Unknown';
                $userActivity[$stat->created_by]['role'] = 'In-Charge';
                $userActivity[$stat->created_by]['created'] = $stat->count;
            }
        }

        foreach ($surveyStats as $stat) {
            if ($stat->created_by) {
                $userActivity[$stat->created_by]['name'] = $stat->creator->name ?? 'Unknown';
                $userActivity[$stat->created_by]['role'] = 'Surveyor';
                $userActivity[$stat->created_by]['surveyed'] = $stat->count;
            }
        }

        foreach ($reportStats as $stat) {
            if ($stat->assignee_reporter) {
                $userActivity[$stat->assignee_reporter]['name'] = $stat->reporter->name ?? 'Unknown';
                $userActivity[$stat->assignee_reporter]['role'] = 'Reporter';
                $userActivity[$stat->assignee_reporter]['reported'] = $stat->count;
            }
        }

        foreach ($checkStats as $stat) {
            if ($stat->assignee_checker) {
                $userActivity[$stat->assignee_checker]['name'] = $stat->checker->name ?? 'Unknown';
                $userActivity[$stat->assignee_checker]['role'] = 'Checker';
                $userActivity[$stat->assignee_checker]['checked'] = $stat->count;
            }
        }

        foreach ($deliveryStats as $stat) {
            if ($stat->assignee_delivery) {
                $userActivity[$stat->assignee_delivery]['name'] = $stat->deliveryPerson->name ?? 'Unknown';
                $userActivity[$stat->assignee_delivery]['role'] = 'Delivery Person';
                $userActivity[$stat->assignee_delivery]['delivered'] = $stat->count;
            }
        }

        // Calculate average timings for reporting & checking
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

        // 3. Operational Efficiencies
        $avgReporting = Work::whereDate('reporting_ended_at', $date)
            ->whereNotNull('reporting_started_at')
            ->whereNotNull('reporting_ended_at')
            ->get()
            ->avg('reporting_duration_minutes') ?? 0;

        $avgChecking = Work::whereDate('checking_ended_at', $date)
            ->whereNotNull('checking_started_at')
            ->whereNotNull('checking_ended_at')
            ->get()
            ->avg('checking_duration_minutes') ?? 0;

        // 4. Detailed Works List
        $detailedWorks = Work::where(function($query) use ($date) {
                $query->whereDate('created_at', $date)
                      ->orWhereHas('inspection', function($q) use ($date) {
                          $q->whereDate('created_at', $date);
                      })
                      ->orWhereDate('reporting_ended_at', $date)
                      ->orWhereDate('checking_ended_at', $date)
                      ->orWhere(function($q) use ($date) {
                          $q->whereDate('updated_at', $date)
                            ->where(function($sub) {
                                $sub->where('delivery_status', 'Delivery Done')
                                    ->orWhereNotNull('result');
                            });
                      });
            })
            ->with(['creator', 'surveyor', 'reporter', 'checker', 'deliveryPerson', 'inspection'])
            ->get();

        // 5. CSV Export Action
        if ($request->input('action') === 'export') {
            return $this->exportCsv($dateStr, $createdCount, $surveyedCount, $reportedCount, $checkedCount, $deliveredCount, $canceledCount, $positiveCount, $negativeCount, $userActivity, $detailedWorks);
        }

        return view('works.daily_report', compact(
            'dateStr',
            'createdCount',
            'surveyedCount',
            'reportedCount',
            'checkedCount',
            'deliveredCount',
            'canceledCount',
            'positiveCount',
            'negativeCount',
            'userActivity',
            'avgReporting',
            'avgChecking',
            'detailedWorks'
        ));
    }

    private function exportCsv($dateStr, $createdCount, $surveyedCount, $reportedCount, $checkedCount, $deliveredCount, $canceledCount, $positiveCount, $negativeCount, $userActivity, $detailedWorks)
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=daily_report_{$dateStr}.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($dateStr, $createdCount, $surveyedCount, $reportedCount, $checkedCount, $deliveredCount, $canceledCount, $positiveCount, $negativeCount, $userActivity, $detailedWorks) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ["DAILY REPORT FOR {$dateStr}"]);
            fputcsv($file, []);

            fputcsv($file, ["SUMMARY METRICS"]);
            fputcsv($file, ["Metric", "Count"]);
            fputcsv($file, ["Created", $createdCount]);
            fputcsv($file, ["Surveyed", $surveyedCount]);
            fputcsv($file, ["Reported", $reportedCount]);
            fputcsv($file, ["Checked", $checkedCount]);
            fputcsv($file, ["Delivered", $deliveredCount]);
            fputcsv($file, ["Canceled", $canceledCount]);
            fputcsv($file, ["Positive", $positiveCount]);
            fputcsv($file, ["Negative", $negativeCount]);
            fputcsv($file, []);

            fputcsv($file, ["STAFF PERFORMANCE"]);
            fputcsv($file, ["User Name", "Role", "Created", "Surveyed", "Reported", "Avg Report Duration (min)", "Checked", "Avg Check Duration (min)", "Delivered"]);
            foreach ($userActivity as $userId => $activity) {
                fputcsv($file, [
                    $activity['name'],
                    $activity['role'],
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

            fputcsv($file, ["DETAILED WORK LOG"]);
            fputcsv($file, ["Work ID", "Applicant", "In-Charge", "Surveyor", "Surveyed At", "Reporter", "Report Start", "Report End", "Report Duration (min)", "Checker", "Check Start", "Check End", "Check Duration (min)", "Delivery Person", "Delivery Status", "Result", "Remarks"]);
            foreach ($detailedWorks as $work) {
                fputcsv($file, [
                    $work->custom_id,
                    $work->name_of_applicant,
                    $work->creator->name ?? '-',
                    $work->surveyor->name ?? '-',
                    $work->inspection ? $work->inspection->created_at->toTimeString() : '-',
                    $work->reporter->name ?? '-',
                    $work->reporting_started_at ? $work->reporting_started_at->toTimeString() : '-',
                    $work->reporting_ended_at ? $work->reporting_ended_at->toTimeString() : '-',
                    $work->reporting_duration_minutes ?? '-',
                    $work->checker->name ?? '-',
                    $work->checking_started_at ? $work->checking_started_at->toTimeString() : '-',
                    $work->checking_ended_at ? $work->checking_ended_at->toTimeString() : '-',
                    $work->checking_duration_minutes ?? '-',
                    $work->deliveryPerson->name ?? '-',
                    $work->delivery_status,
                    $work->result ?? '-',
                    $work->remarks ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
