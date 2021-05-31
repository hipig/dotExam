<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'paper_id',
        'title',
        'description',
        'item_score',
        'item_count',
        'status',
        'index',
    ];

    protected $appends = [
        'long_title',
        'short_title',
    ];

    public function paper()
    {
        return $this->belongsTo(Paper::class, 'paper_id');
    }

    public function items()
    {
        return $this->hasMany(PaperItem::class, 'section_id')->orderBy('index') ->orderBy('question_type');
    }

    public function getLongTitleAttribute()
    {
        return sprintf("%s（本类题共%s题，每小题%s分，共%s分，%s）",
            $this->title,
            $this->item_count,
            $this->item_score,
            $this->item_count * $this->item_score,
            $this->description
        );
    }

    public function getShortTitleAttribute()
    {
        return sprintf("%s（共%s题，每题%s分）",
            $this->title,
            $this->item_count,
            $this->item_score
        );
    }
}
