<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Relative;
use App\Models\Inspection;
use App\Models\Document;

class Work extends Model
{
    use HasFactory;

    protected $table = 'works';

    protected $fillable = [
        // Work Information
        'custom_id',
        'assignment_date',
        
        // Applicant Information
        'created_by',
        'name_of_applicant',
        'number_of_applicants',

        // Bank Details
        'bank_name',
        'bank_branch',

        // Address Details
        'source',
        'address_line_1',
        'address_line_2',
        'state',
        'district',
        'pin_code',
        'post_office',
        'police_station',

        // Loan Details
        'project_name',
        'loan_amount_requested',
        'loan_type',

        // File Uploads
        'pdf_1',
        'final_report_pdf', // New column
        'final_report_word', // New column

        // Assigned Users
        'assignee_surveyor',
        'assignee_reporter',
        'assignee_checker',
        'assignee_delivery',

        // Status & Payment
        'status',
        'work_type',
        'valuer',
        'payment_status',
        'delivery_status',
        // Result & Remarks
        'result',
        'remarks',
        // Print Status
        'is_printed',
        'report_submit_date',
        // Reporting & Checking Time Tracking
        'reporting_started_at',
        'reporting_ended_at',
        'checking_started_at',
        'checking_ended_at',
        // VDN Status
        'is_vdn',
        // Billing
        'is_billing_done',
        'billing_done_at',
        'billing_done_by',
        'invoice_number',
        'invoice_date',
        'invoice_amount',
        'amount_without_gst',
        'gst_amount',
    ];

    protected $casts = [
        'assignment_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_printed' => 'boolean',
        'is_vdn' => 'boolean',
        'is_billing_done' => 'boolean',
        'billing_done_at' => 'datetime',
        'invoice_date' => 'date',
        'report_submit_date' => 'date',
        'reporting_started_at' => 'datetime',
        'reporting_ended_at' => 'datetime',
        'checking_started_at' => 'datetime',
        'checking_ended_at' => 'datetime',
        'invoice_amount' => 'decimal:2',
        'amount_without_gst' => 'decimal:2',
        'gst_amount' => 'decimal:2',
    ];

    /**
     * Relationships with User Model
     */
    public function surveyor()
    {
        return $this->belongsTo(User::class, 'assignee_surveyor');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'assignee_reporter');
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'assignee_checker');
    }

    public function deliveryPerson()
    {
        return $this->belongsTo(User::class, 'assignee_delivery');
    }

    public function bankBranch()
    {
        return $this->belongsTo(User::class, 'bank_branch');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function billingDoneBy()
    {
        return $this->belongsTo(User::class, 'billing_done_by');
    }

    public function relatives()
    {
        return $this->hasMany(Relative::class);
    }

    public function inspection()
    {
        return $this->hasOne(Inspection::class);
    }
    public function report()
    {
        return $this->hasOne(Report::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get reporting duration in minutes (null if incomplete).
     */
    public function getReportingDurationMinutesAttribute(): ?int
    {
        if ($this->reporting_started_at && $this->reporting_ended_at) {
            return (int) $this->reporting_started_at->diffInMinutes($this->reporting_ended_at);
        }
        return null;
    }

    /**
     * Get checking duration in minutes (null if incomplete).
     */
    public function getCheckingDurationMinutesAttribute(): ?int
    {
        if ($this->checking_started_at && $this->checking_ended_at) {
            return (int) $this->checking_started_at->diffInMinutes($this->checking_ended_at);
        }
        return null;
    }
}
