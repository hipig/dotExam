<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;
        $type = $faker->randomElement(array_keys(Question::$typeMap));
        switch ($type) {
            case 1:
                $keys = ['A', 'B', 'C', 'D'];
                for ($i = 0; $i < count($keys); $i++) {
                    $options[$keys[$i]] = $faker->sentence;
                }
                $answer = $faker->randomElement($keys);
                break;
            case 3:
                $keys = ['A', 'B'];
                for ($i = 0; $i < count($keys); $i++) {
                    $options[$keys[$i]] = $faker->sentence;
                }
                $answer = $faker->randomElement($keys);
                $score = 2;
                break;
            case 2:
                $keys = ['A', 'B', 'C', 'D', 'E'];
                for ($i = 0; $i < count($keys); $i++) {
                    $options[$keys[$i]] = $faker->sentence;
                }
                $answer = $faker->randomElements($keys, $faker->randomElement([2, 3]));
                break;
            case 4:
                $options = [];
                for ($i = 0; $i < $faker->randomElement([1, 2, 3]); $i++) {
                    $answer[] = $faker->word;
                }
                $score = 5;
                break;
            default:
                $options = [];
                $answer = '';
                $score = 10;
        }

        return [
            'title' => $faker->text,
            'type' => $type,
            'option' => $options,
            'answer' => $answer,
            'material' => $faker->paragraph,
            'parse' => $faker->paragraph,
            'score' => $score ?? 1,
            'difficulty' => $faker->randomElement(array_keys(Question::$difficultyMap)),
        ];
    }
}
