<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->items()->delete();
        });
    }

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
        $this->load('items');
        switch ($this->type) {
            case Paper::TYPE_CHAPTER:
                $items = PaperItem::query()
                    ->whereIn('id', $this->paper_item_ids)
                    ->orderBy('index')
                    ->orderBy('question_type')
                    ->get();
                $items = $items->map(function ($item) {
                    $item->record = $this->items
                        ->where('user_id', Auth::id())
                        ->where('paper_item_id', $item->id)
                        ->first();

                    return $item;
                });
                break;
            case Paper::TYPE_MOCK:
            case Paper::TYPE_OLD:
                $paper = $this->paper;
                if ($paper->has_section) {
                    $sectionItems = $paper->load(['sections.items'])->sections;
                    $items = $sectionItems->map(function ($sectionItem) {
                        $sectionItem->items = $sectionItem->items->map(function ($item) {
                            $item->record = $this->items
                                ->where('user_id', Auth::id())
                                ->where('paper_item_id', $item->id)
                                ->first();
                            return $item;
                        });
                        return $sectionItem;
                    });
                } else {
                    $items = $paper->items->map(function ($item) {
                        $item->record = $this->items
                            ->where('user_id', Auth::id())
                            ->where('paper_item_id', $item->id)
                            ->first();
                        return $item;
                    });
                }
                break;
            case Paper::TYPE_DAILY:
                $items = $this->paper->items;
                break;
        }

        return $items;
    }

    public function getModeAttribute()
    {
        return $this->setting['mode'] ?? self::MODE_EXERCISE;
    }

    public function getPaperItemIdsAttribute()
    {
        return $this->setting['paper_items'] ?? [];
    }

    public function getResultUrlAttribute()
    {
        return route('paperRecords.showResult', $this);
    }

    public function getItemsResult($type = 1)
    {
        $query = $this->items()->where('question_type', $type);

        return [
            'type' => $type,
            'total' => (clone $query)->count(),
            'right' => (clone $query)->where('is_correct', PaperRecordItem::CORRECT_TYPE_ALL_RIGHT)->count(),
            'error' => (clone $query)->where('is_correct', '<>', PaperRecordItem::CORRECT_TYPE_ALL_RIGHT)->count(),
            'score' => (clone $query)->where('is_correct', PaperRecordItem::CORRECT_TYPE_ALL_RIGHT)->sum('score'),
        ];
    }
}
