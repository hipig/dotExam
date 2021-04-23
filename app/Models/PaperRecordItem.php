<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperRecordItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'record_id',
        'subject_id',
        'paper_id',
        'paper_item_id',
        'question_id',
        'question_type',
        'answer',
        'is_right',
        'score',
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


    public function checkAnswer($answer)
    {
        $correctAnswer = $this->question->answer;
        $result = 0;
        switch ($this->question_type) {
            case self::SINGLE_SELECT:
            case self::JUDGE_SELECT:
                $result = hash_equals($answer, $correctAnswer);
                break;
            case self::MULTI_SELECT:
                $answerMap = is_array($answer) ? $answer : explode(',', $answer);
                $answerCount = count($answerMap);
                $correctAnswerCount = count($correctAnswer);
                // 答案个数为0直接判定错误
                if ($answerCount === 0) {
                    break;
                }
                // 超过正确答案个数直接判定错误
                if ($answerCount > $correctAnswerCount) {
                    $result = -1;
                    break;
                }

                // 完全匹配
                $correctCount = 0;
                $result = 1;
                foreach ($answerMap as $key => $value) {
                    if (!in_array($value, $correctAnswer)) {
                        $result = -1;
                        break;
                    }
                    $correctCount++;
                }

                if ($correctCount < $correctAnswerCount) {
                    $result = 2;
                }

                break;
        }

        return $result;
    }
}
