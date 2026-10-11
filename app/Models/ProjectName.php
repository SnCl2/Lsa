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
        'bank_name',
        'bank_id',
        'bank_branch',
    ];

    protected $casts = [
        'project_rate' => 'decimal:2',
    ];

    /**
     * Relationship to BankName model
     */
    public function bank()
    {
        return $this->belongsTo(BankName::class, 'bank_name', 'name');
    }

    /**
     * Relationship to User model for Bank Branch
     */
    public function bankBranch()
    {
        return $this->belongsTo(User::class, 'bank_branch');
    }

    /**
     * Relationship to works with this project name
     */
    public function works()
    {
        return $this->hasMany(Work::class, 'project_name', 'name');
    }
}
