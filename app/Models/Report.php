<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Work;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'work_id',
        'report_data',
    ];

    protected $casts = [
        'report_data' => 'array', // Automatically cast JSON to an array
    ];

    /**
     * Define relationship with Work model.
     */
    public function work()
    {
        return $this->belongsTo(Work::class);
    }
}
