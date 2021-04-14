<?php

namespace Database\Factories;

use App\Models\Paper;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaperFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Paper::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;
        $type = $faker->randomElement(array_keys(Paper::$typeMap));
        $subjectIds = Subject::query()->where('level', 2)->pluck('id')->toArray();

        $parentId = null;
        $subjectId = $faker->randomElement($subjectIds);
        if ($type === Paper::TYPE_CHAPTER) {
            $papers = Paper::query()->whereNull('parent_id')->where('type', Paper::TYPE_CHAPTER)->get();
            $paperIds = $papers->pluck('id')->toArray();
            $paperIds[] = null;
            $parentId = $faker->randomElement($paperIds);
            if ($parentId) {
                $subjectId = $papers->find($parentId)->subject_id;
            }
        }

        return [
            'title' => $faker->sentence,
            'subject_id' => $subjectId,
            'parent_id' => $parentId,
            'type' => $type,
            'has_section' => false,
            'time_limit' => in_array($type, [Paper::TYPE_MOCK, Paper::TYPE_OLD]) ? $faker->randomElement([90, 120, 180]) : 0,
        ];
    }
}
