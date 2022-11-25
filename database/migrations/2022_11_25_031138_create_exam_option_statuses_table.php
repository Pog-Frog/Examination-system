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
        Schema::create('exam_option_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade')->onUpdate('cascade')->references('id')->on('exams');
            $table->foreignId('option_id')->constrained()->onDelete('cascade')->onUpdate('cascade')->references('id')->on('exam_options');
            $table->string('status'); // 'published', 'unpublished'
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
        Schema::dropIfExists('exam_option_statuses');
    }
};
