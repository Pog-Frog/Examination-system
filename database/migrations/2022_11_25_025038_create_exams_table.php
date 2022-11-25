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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('description');
            $table->string('max_attempts');
            $table->string('duration');
            $table->string('total_mark');
            $table->foreignId('classroom_instructor_id')->constrained()->onDelete('cascade')->onUpdate('cascade')->references('id')->on('classroom_instructors');
            $table->string('publish_status'); // 'true', 'false'
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
        Schema::dropIfExists('exams');
    }
};
