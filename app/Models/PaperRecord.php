<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperRecord extends Model
{
    use HasFactory;

    const MODE_EXERCISE = 'exercise';
    const MODE_TEST = 'test';
    public static $modeMap = [
        self::MODE_EXERCISE => '练习模式',
        self::MODE_TEST => '考试模式',
    ];

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

    protected $casts = [
        'setting'  => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function paper()
    {
        return $this->belongsTo(Paper::class, 'paper_id');
    }

    public function items()
    {
        return $this->hasMany(PaperRecordItem::class, 'record_id');
    }

    public function getPaperItemsAttribute()
    {
        switch ($this->type) {
            case Paper::TYPE_CHAPTER:
                $items = PaperItem::query()
                    ->whereIn('id', $this->paper_item_ids)
                    ->orderBy('index')
                    ->orderBy('question_type')
                    ->get();
                break;
            case Paper::TYPE_MOCK:
            case Paper::TYPE_OLD:
                $items = $this->paper->has_section ?
                    $this->paper->load('sections.items')->sections :
                    $this->paper->items;
                break;
            case Paper::TYPE_DAILY:
                $items = $this->paper->items;
                break;
        }

        return $items;
    }

    public function getSettingModeAttribute()
    {
        return json_decode($this->setting);
    }

    public function getModeAttribute()
    {
        return $this->setting['mode'] ?? self::MODE_EXERCISE;
    }

    public function getPaperItemIdsAttribute()
    {
        return $this->setting['paper_items'] ?? [];
    }
}
