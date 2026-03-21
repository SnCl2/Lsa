<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['document_name', 'date_of_issue', 'image', 'work_id'];


    public function work()
    {
        return $this->belongsTo(Work::class);
    }
}

