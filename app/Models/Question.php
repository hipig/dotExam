<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    const DIFFICULTY_SIMPLE = 1;
    const DIFFICULTY_MEDIUM = 2;
    const DIFFICULTY_HARD = 3;
    public static $difficultyMap = [
        self::DIFFICULTY_SIMPLE => '简单',
        self::DIFFICULTY_MEDIUM => '中等',
        self::DIFFICULTY_HARD => '困难',
    ];

    const SINGLE_SELECT = 1;
    const MULTI_SELECT = 2;
    const JUDGE_SELECT = 3;
    const FILL_BLANK = 4;
    const SHORT_ANSWER = 5;
    public static $typeMap = [
        self::SINGLE_SELECT => '单选题',
        self::MULTI_SELECT => '多选题',
        self::JUDGE_SELECT => '判断题',
        self::FILL_BLANK => '填空题',
        self::SHORT_ANSWER => '问答题'
    ];

    public static $needDecodeTypeMap = [
        self::MULTI_SELECT,
        self::FILL_BLANK
    ];

    protected $fillable = [
        'subject_id',
        'title',
        'type',
        'option',
        'answer',
        'material',
        'parse',
        'score',
        'difficulty',
        'status',
        'index'
    ];

    protected $casts = [
        'option' => 'array',
    ];

    public function setAnswerAttribute($value)
    {
        $this->attributes['answer'] = is_array($value) ? json_encode($value) : $value;
    }

    public function getAnswerAttribute($value)
    {
        return in_array($this->type, self::$needDecodeTypeMap) ? json_decode($value) : $value;
    }

    public function getAnswerTextAttribute($value)
    {
        return in_array($this->type, self::$needDecodeTypeMap) ? implode(', ', $this->answer) : $this->answer;
    }
}
