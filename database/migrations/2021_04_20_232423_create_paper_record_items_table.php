<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaperRecordItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paper_record_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedBigInteger('record_id')->comment('记录ID');
            $table->unsignedBigInteger('subject_id')->comment('科目ID');
            $table->unsignedBigInteger('paper_id')->comment('试卷ID');
            $table->unsignedBigInteger('paper_item_id')->comment('试卷题目ID');
            $table->unsignedBigInteger('question_id')->comment('题目ID');
            $table->unsignedTinyInteger('question_type')->default(1)->comment('题目类型');
            $table->string('answer', 2560)->nullable()->comment('答案');
            $table->tinyInteger('is_correct')->default(0)->comment('是否答对');
            $table->unsignedInteger('score')->default(0)->comment('得分');
            $table->text('check_remark')->nullable()->comment('阅卷备注');
            $table->timestamps();
            $table->unique(['user_id', 'record_id', 'paper_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paper_record_items');
    }
}
