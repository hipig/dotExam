<?php

namespace Database\Factories;

use App\Models\Paper;
use App\Models\PaperItem;
use App\Models\PaperSection;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaperItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaperItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;
        $papers = Paper::query()->whereNotNull('parent_id')->where('type', Paper::TYPE_CHAPTER)->orWhere(function ($query) {
            $query->whereIn('type', [Paper::TYPE_MOCK, Paper::TYPE_OLD, Paper::TYPE_DAILY]);
        })->get();

        $paperId = $faker->randomElement($papers->pluck('id')->toArray());
        $paper = $papers->find($paperId);
        $hasSection = $paper->has_section ?? false;

        $sectionId = null;
        $questionType = $faker->randomElement(array_keys(Question::$typeMap));
        $scores = [1 => 2, 2 => 3, 3 => 1, 4 => 5, 5 => 10 ];
        $score = $scores[$questionType];
        if ($hasSection) {
            $sections = PaperSection::query()->where('paper_id', $paperId)->get();
            $sectionId = $faker->randomElement($sections->pluck('id')->toArray());
            $section = $sections->find($sectionId);
            $score = $section->item_score ?? 0;
        }

        $questionIds = Question::query()->pluck('id')->toArray();
        $questionId = $faker->randomElement($questionIds);

        return [
            'subject_id' => $paper->subject_id,
            'paper_id' => $paperId,
            'section_id' => $sectionId,
            'question_id' => $questionId,
            'question_type' => $questionType,
            'score' => $score
        ];
    }
}
