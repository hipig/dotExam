<?php

namespace App\Models;

use App\Models\Traits\OrderIndexScopeTrait;
use App\Models\Traits\StatusScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
