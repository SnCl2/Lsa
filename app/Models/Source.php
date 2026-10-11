<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;

    protected $table = 'sources';

    protected $fillable = [
        'name',
    ];

    /**
     * Relationship to works associated with this source
     */
    public function works()
    {
        return $this->hasMany(Work::class, 'source', 'name');
    }
}
