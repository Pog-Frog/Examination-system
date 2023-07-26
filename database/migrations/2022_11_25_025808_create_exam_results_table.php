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
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_student_id')->constrained()->onDelete('cascade')->onUpdate('cascade')->references('id')->on('classroom_students');
            $table->foreignId('exam_id')->constrained()->onDelete('cascade')->onUpdate('cascade')->references('id')->on('exams');
            $table->string('student_attempts');
            $table->string('marks');
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
        Schema::dropIfExists('exam_results');
    }
};
