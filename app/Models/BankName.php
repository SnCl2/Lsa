<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankName extends Model
{
    use HasFactory;

    protected $table = 'bank_names';

    protected $fillable = [
        'name',
    ];

    /**
     * Relationship to works with this bank name
     */
    public function works()
    {
        return $this->hasMany(Work::class, 'bank_name', 'name');
    }
}
