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
        Schema::create('announcement_comments', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->dateTime('date_created');
            $table->foreignId('announcement_id')->constrained()->onDelete('cascade')->onUpdate('cascade')->references('id')->on('announcements');
            $table->string('author_id');
            $table->string('author_role'); // 'student' or 'instructor'
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
        Schema::dropIfExists('announcement_comments');
    }
};
