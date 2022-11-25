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
        Schema::create('exam_options', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        DB::table('exam_options')->insert(
            array(
                ['name' => 'Qustions Random order'],
                ['name' => 'Disable browser switch'],
                ['name' => 'Plagarism checking'],
                ['name' => 'Face recognition'],
                ['name' => 'Automatic grading']
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
        Schema::dropIfExists('exam_options');
    }
};
