<?php

namespace App\Models;

use App\Models\Traits\OrderIndexScopeTrait;
use App\Models\Traits\StatusScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Paper extends Model
{
    use HasFactory, StatusScope, OrderIndexScopeTrait;

    const TYPE_CHAPTER = 'chapter';
    const TYPE_MOCK = 'mock';
    const TYPE_OLD = 'old';
    const TYPE_DAILY = 'daily';
    public static $typeMap = [
        self::TYPE_CHAPTER => '章节练习',
        self::TYPE_MOCK => '模拟考试',
        self::TYPE_OLD => '历年真题',
        self::TYPE_DAILY => '每日一练',
    ];

    const FILTER_TYPE_ALL = 'all';
    const FILTER_TYPE_UNDONE = 'undone';
    const FILTER_TYPE_DONE = 'done';
    const FILTER_TYPE_ERROR = 'error';
    public static $filterTypeMap = [
        self::FILTER_TYPE_ALL => '全部',
        self::FILTER_TYPE_UNDONE => '未做',
        self::FILTER_TYPE_DONE => '已做',
        self::FILTER_TYPE_ERROR => '错题',
    ];

    const FILTER_SIZE_5 = 5;
    const FILTER_SIZE_10 = 10;
    const FILTER_SIZE_20 = 20;
    const FILTER_SIZE_30 = 30;
    const FILTER_SIZE_50 = 50;
    const FILTER_SIZE_100 = 100;
    public static $filterSizeMap = [
        self::FILTER_SIZE_5 => 5,
        self::FILTER_SIZE_10 => 10,
        self::FILTER_SIZE_20 => 20,
        self::FILTER_SIZE_30 => 30,
        self::FILTER_SIZE_50 => 50,
        self::FILTER_SIZE_100 => 100,
    ];

    protected $fillable = [
        'subject_id',
        'parent_id',
        'user_id',
        'title',
        'type',
        'has_section',
        'time_limit',
        'total_score',
        'total_count',
        'done_count',
        'source',
        'description',
        'status',
        'index',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function parent()
    {
        return $this->belongsTo(Paper::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Paper::class, 'parent_id');
    }

    public function sections()
    {
        return $this->hasMany(PaperSection::class, 'paper_id');
    }

    public function items()
    {
        return $this->hasMany(PaperItem::class, 'paper_id');
    }

    public function childrenItems()
    {
        return $this->hasManyThrough(PaperItem::class, Paper::class, 'parent_id', 'paper_id');
    }

    public function topThreeItems()
    {
        return $this->hasMany(PaperItem::class, 'paper_id')->orderBy('question_type')->limit(3);
    }

    public function getHasChildrenAttribute()
    {
        return $this->newModelQuery()->where('parent_id', $this->id)->exists();
    }

    public function allItems()
    {
        return $this->has_children ? $this->childrenItems() : $this->items();
    }

    public function getTotalities($filterType = 'all')
    {
        $query = $this->allItems();
        switch ($filterType) {
            case self::FILTER_TYPE_UNDONE:
                break;
            case self::FILTER_TYPE_DONE:
                break;
            case self::FILTER_TYPE_ERROR:
                break;
        }

        return [
            0 => (clone $query)->count(),
            Question::SINGLE_SELECT => (clone $query)->where('question_type', Question::SINGLE_SELECT)->count(),
            Question::MULTI_SELECT => (clone $query)->where('question_type', Question::MULTI_SELECT)->count(),
            Question::JUDGE_SELECT => (clone $query)->where('question_type', Question::JUDGE_SELECT)->count(),
            Question::FILL_BLANK => (clone $query)->where('question_type', Question::FILL_BLANK)->count(),
            Question::SHORT_ANSWER => (clone $query)->where('question_type', Question::SHORT_ANSWER)->count(),
        ];
    }

    public function storeChapterRecord($range = 'all', $type = 0, $size = 5, $mode = 'exercise')
    {
        $bankItems = $this->allItems();

        switch ($range) {
            case self::FILTER_TYPE_UNDONE:
                break;
            case self::FILTER_TYPE_DONE:
                break;
            case self::FILTER_TYPE_ERROR:
                break;
        }

        if ($type) {
            $bankItems->where('paper_items.question_type', $type);
        }

        $idPluck = $bankItems->pluck('paper_items.id');
        $count = $idPluck->count();
        $ids = $idPluck->random($size >= $count ? $count : $size);

        $setting = [
            'paper_items' => $ids->toArray(),
            'mode' => $mode
        ];

        $record = new PaperRecord([
            'type' => $this->type,
            'total_count' => $ids->count(),
            'setting' => $setting
        ]);
        $record->user()->associate(Auth::user());
        $record->subject()->associate($this->subject);
        $record->paper()->associate($this);
        $record->save();

        return $record;
    }
}
