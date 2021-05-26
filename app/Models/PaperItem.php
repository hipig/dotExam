<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public function recordItems()
    {
        return $this->hasMany(PaperRecordItem::class, 'paper_item_id');
    }

    public function getRecord(PaperRecord $record)
    {
        return $this->recordItems()
            ->where('user_id', Auth::id())
            ->where('record_id', $record->id)
            ->first();
    }
}
