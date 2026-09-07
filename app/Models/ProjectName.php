<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectName extends Model
{
    use HasFactory;

    protected $table = 'project_names';

    protected $fillable = [
        'name',
    ];
}
