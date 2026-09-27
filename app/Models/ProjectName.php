<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectName extends Model
{
    use HasFactory;

    protected $table = 'project_names';

    const TYPE_NORMAL = 'Normal';
    const TYPE_APPROVED = 'Approved';
    const TYPE_SCREEN = 'Screen';

    const TYPES = [
        self::TYPE_NORMAL,
        self::TYPE_APPROVED,
        self::TYPE_SCREEN,
    ];

    protected $fillable = [
        'name',
        'project_type',
        'project_rate',
    ];

    protected $casts = [
        'project_rate' => 'decimal:2',
    ];
}
