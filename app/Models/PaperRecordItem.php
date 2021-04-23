<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperRecordItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject_id',
        'paper_id',
        'paper_item_id',
        'question_id',
        'question_type',
        'answer',
        'is_right',
        'score',
    ];

    public function record()
    {
        return $this->belongsTo(PaperRecord::class, 'record_id');
    }
}
