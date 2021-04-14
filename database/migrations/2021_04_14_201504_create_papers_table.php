<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('papers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id')->comment('科目ID');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('上级ID');
            $table->unsignedBigInteger('user_id')->nullable()->comment('用户ID');
            $table->string('title')->comment('试卷名称');
            $table->string('type')->default(\App\Models\Paper::TYPE_CHAPTER)->comment('类型');
            $table->boolean('has_section')->default(false)->comment('是否部分模式');
            $table->unsignedInteger('time_limit')->default(0)->comment('时间限制（分）');
            $table->unsignedInteger('total_score')->default(0)->comment('总分');
            $table->unsignedInteger('total_count')->default(0)->comment('总题数');
            $table->unsignedInteger('done_count')->default(0)->comment('学习次数');
            $table->string('source')->nullable()->comment('来源');
            $table->text('description')->nullable()->comment('描述');
            $table->boolean('status')->default(true)->comment('状态');
            $table->unsignedInteger('index')->default(99)->comment('排序');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('papers');
    }
}
