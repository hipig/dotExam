<?php

namespace Database\Factories;

use App\Models\Paper;
use App\Models\PaperSection;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaperSectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaperSection::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;
        $paperIds = Paper::query()->whereIn('type', [Paper::TYPE_MOCK, Paper::TYPE_OLD])->pluck('id')->toArray();
        $paperId = $faker->randomElement($paperIds);
        $paper = Paper::query()->where('id', $paperId)->first();
        $type = $faker->randomElement(array_keys(Question::$typeMap));
        $scores = [1 => 2, 2 => 3, 3 => 1, 4 => 5, 5 => 10 ];
        $score = $scores[$type];

        return [
            'subject_id' => $paper->subject_id,
            'paper_id' => $paperId,
            'title' => $faker->title,
            'description' => $faker->title,
            'item_score' => $score
        ];
    }
}
