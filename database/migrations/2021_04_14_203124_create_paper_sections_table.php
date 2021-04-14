<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaperSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paper_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id')->comment('科目ID');
            $table->unsignedBigInteger('paper_id')->comment('试卷ID');
            $table->string('title')->comment('部分标题');
            $table->string('description')->nullable()->comment('部分描述');
            $table->unsignedInteger('item_score')->default(0)->comment('小题分数');
            $table->unsignedInteger('item_count')->default(0)->comment('小题数量');
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
        Schema::dropIfExists('paper_sections');
    }
}
