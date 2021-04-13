<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seedFile = file_get_contents(storage_path('seeders/subject.json'));
        $seedData = json_decode($seedFile, true);

        foreach ($seedData as $data) {
            $this->createSubject($data);
        }
    }

    protected function createSubject($data, $parent = null)
    {
        // 创建一个新的类目对象
        $subject = new Subject(['title' => $data['title'], 'name' => $data['name'], 'trait' => $data['trait'] ?? 'special']);
        // 如果有 children 字段则代表这是一个父类目
        $subject->is_directory = isset($data['children']);
        // 如果有传入 $parent 参数，代表有父类目
        if (!is_null($parent)) {
            $subject->parent()->associate($parent);
        }
        //  保存到数据库
        $subject->save();
        // 如果有 children 字段并且 children 字段是一个数组
        if (isset($data['children']) && is_array($data['children'])) {
            // 遍历 children 字段
            foreach ($data['children'] as $child) {
                // 递归调用 createSubject 方法，第二个参数即为刚刚创建的类目
                $this->createSubject($child, $subject);
            }
        }
    }
}
