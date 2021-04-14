<?php

namespace Database\Seeders;

use App\Models\PaperSection;
use Illuminate\Database\Seeder;

class PaperSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaperSection::factory(100)->create()->each(function ($item) {
            if (!$item->paper->has_section) {
                $item->paper->has_section = true;
                $item->paper->save();
            }
        });
    }
}
