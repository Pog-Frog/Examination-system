<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_exam_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_student_id')->constrained()->onDelete('cascade')->onUpdate('cascade')->references('id')->on('classroom_students');
            $table->foreignId('exam_question_id')->constrained()->onDelete('cascade')->onUpdate('cascade')->references('id')->on('exam_questions');
            $table->string('grade');
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
        Schema::dropIfExists('student_exam_answers');
    }
};
