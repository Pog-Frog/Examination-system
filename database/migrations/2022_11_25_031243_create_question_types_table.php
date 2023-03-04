<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('question_types', function (Blueprint $table) {
            $table->id();
            $table->string('type_name')->unique();
            $table->timestamps();
        });

        DB::table('question_types')->insert(
            array(
                ['type_name' => 'MCQ'],
                ['type_name' => 'True False'],
                ['type_name' => 'Fill in the blanks'],
                ['type_name' => 'Essay']
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_types');
    }
};
