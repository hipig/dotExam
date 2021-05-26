<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperRecordItem extends Model
{
    use HasFactory;

    const CORRECT_TYPE_ERROR = -1;
    const CORRECT_TYPE_ALL_RIGHT = 1;
    const CORRECT_TYPE_PART_RIGHT = 2;
    public static $correctTypeMap = [
        self::CORRECT_TYPE_ERROR => '错误',
        self::CORRECT_TYPE_ALL_RIGHT => '正确',
        self::CORRECT_TYPE_PART_RIGHT => '部分正确',
    ];

    public static $rightTypeMap = [
        self::CORRECT_TYPE_ALL_RIGHT,
        self::CORRECT_TYPE_PART_RIGHT,
    ];

    protected $fillable = [
        'user_id',
        'record_id',
        'subject_id',
        'paper_id',
        'paper_item_id',
        'question_id',
        'question_type',
        'answer',
        'score',
        'check_remark',
    ];

    protected $appends = [
        'answer_text',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function record()
    {
        return $this->belongsTo(PaperRecord::class, 'record_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function paper()
    {
        return $this->belongsTo(Paper::class, 'paper_id');
    }

    public function paperItem()
    {
        return $this->belongsTo(PaperItem::class, 'paper_item_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function setAnswerAttribute($value)
    {
        $this->attributes['answer'] = is_array($value) ? json_encode($value) : $value;
    }

    public function getAnswerAttribute($value)
    {
        return in_array($this->question_type, Question::$needDecodeTypeMap) ? json_decode($value) : $value;
    }

    public function getAnswerTextAttribute($value)
    {
        return in_array($this->question_type, Question::$needDecodeTypeMap) ? implode(', ', $this->answer) : $this->answer;
    }

    public static function checkAnswer($answer, $correctAnswer, $type)
    {
        $result = 0;
        switch ($type) {
            case Question::SINGLE_SELECT:
            case Question::JUDGE_SELECT:
                $result = hash_equals($answer, $correctAnswer) ? self::CORRECT_TYPE_ALL_RIGHT : self::CORRECT_TYPE_ERROR;
                break;
            case Question::MULTI_SELECT:
                $answerMap = is_array($answer) ? $answer : explode(',', $answer);
                $answerCount = count($answerMap);
                $correctAnswerCount = count($correctAnswer);
                // 答案个数为0直接判定错误
                if ($answerCount === 0) {
                    break;
                }
                // 超过正确答案个数直接判定错误
                if ($answerCount > $correctAnswerCount) {
                    $result = self::CORRECT_TYPE_ERROR;
                    break;
                }

                // 完全匹配
                $result = $answerCount < $correctAnswerCount ? self::CORRECT_TYPE_PART_RIGHT : self::CORRECT_TYPE_ALL_RIGHT;
                foreach ($answerMap as $key => $value) {
                    if (!in_array($value, $correctAnswer)) {
                        $result = self::CORRECT_TYPE_ERROR;
                        break 2;
                    }
                }
                break;
        }

        return $result;
    }
}
