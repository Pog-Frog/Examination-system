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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subject'); // Computer Science, Mathematics, Physics, Chemistry, Biology, English
            $table->string('category'); // EX-> Subject: Computer Science, Category: database design
            $table->string('text');
            $table->foreignId('type_id')->constrained()->onDelete('cascade')->onUpdate('cascade')->references('id')->on('question_types');
            $table->string('grade');
            $table->foreignId('instructor_id')->constrained()->onDelete('cascade')->onUpdate('cascade')->references('id')->on('instructors');
            $table->string('status'); // 'true' => public or 'false' => private
            $table->string('slug')->unique();
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade')->onUpdate('cascade')->references('id')->on('classrooms');
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
        Schema::dropIfExists('questions');
    }
};
