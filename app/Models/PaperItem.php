<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'paper_id',
        'section_id',
        'question_id',
        'question_type',
        'score',
        'status',
        'index',
    ];

    protected $with = [
        'question'
    ];

    public function paper()
    {
        return $this->belongsTo(Paper::class, 'paper_id');
    }

    public function section()
    {
        return $this->belongsTo(PaperSection::class, 'section_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
