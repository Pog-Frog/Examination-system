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
        Schema::create('cheating_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_exam_answer_id')->constrained()->onDelete('cascade')->onUpdate('cascade')->references('id')->on('student_exam_answers');
            $table->foreignId('cheater_id')->constrained()->onDelete('cascade')->onUpdate('cascade')->references('id')->on('student_exam_answers');
            $table->string('plagarism_percentage');
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
        Schema::dropIfExists('cheating_attempts');
    }
};
