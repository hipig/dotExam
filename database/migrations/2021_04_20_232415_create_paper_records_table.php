<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaperRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paper_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedBigInteger('subject_id')->comment('科目ID');
            $table->unsignedBigInteger('paper_id')->comment('试卷ID');
            $table->string('type')->default(\App\Models\Paper::TYPE_CHAPTER)->comment('类型');
            $table->unsignedInteger('score')->default(0)->comment('得分');
            $table->unsignedInteger('total_count')->default(0)->comment('总题数');
            $table->unsignedInteger('done_count')->default(0)->comment('已做题数');
            $table->unsignedInteger('done_time')->default(0)->comment('已做时间（秒）');
            $table->json('setting')->nullable()->comment('答题配置');
            $table->boolean('is_end')->default(false)->comment('是否交卷');
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
        Schema::dropIfExists('paper_records');
    }
}
