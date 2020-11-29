<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizdetails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id');
            $table->integer('questionnumber');
            $table->string('questiontext', 1000);
            $table->char('category', 50);
            $table->char('subcategory', 50);

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
        Schema::dropIfExists('quizzdetails');
    }
}
