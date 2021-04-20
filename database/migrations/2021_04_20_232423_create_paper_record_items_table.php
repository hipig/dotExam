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
            $table->unsignedBigInteger('subject_id')->comment('科目ID');
            $table->unsignedBigInteger('paper_id')->comment('试卷ID');
            $table->unsignedBigInteger('paper_item_id')->comment('试卷题目ID');
            $table->unsignedBigInteger('question_id')->comment('题目ID');
            $table->unsignedTinyInteger('question_type')->default(1)->comment('题目类型');
            $table->string('answer', 2560)->nullable()->comment('答案');
            $table->boolean('is_right')->nullable()->comment('是否答对');
            $table->unsignedInteger('score')->default(0)->comment('得分');
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
        Schema::dropIfExists('paper_record_items');
    }
}
