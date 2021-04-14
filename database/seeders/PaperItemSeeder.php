<?php

namespace Database\Seeders;

use App\Models\Paper;
use App\Models\PaperItem;
use Illuminate\Database\Seeder;

class PaperItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaperItem::factory(3000)->create()->each(function ($item) {
            $paper = $item->paper;

            if ($item->section) {
                $section = $item->section;
                $section->increment('item_count');
                $section->save();
            }

            $paper->increment('total_count');
            in_array($paper->type, [Paper::TYPE_MOCK, Paper::TYPE_OLD]) && $paper->increment('total_score', $item->score);
            if ($paper->parent) {
                $paper->parent->increment('total_count');
            }
        });
    }
}
