<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject_id',
        'paper_id',
        'type',
        'total_count',
        'done_count',
        'done_time',
        'setting',
    ];
}
