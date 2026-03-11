<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Work;
class Relative extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'phone_number',
        'relation',
        'relative_name',
        'pan_number',
        'address',
        'work_id',
    ];

    public function work()
    {
        return $this->belongsTo(Work::class);
    }
}
