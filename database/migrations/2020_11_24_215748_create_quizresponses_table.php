<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizresponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizresponses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('quiz_id');
            $table->integer('Open')->default(1);   // 1 = Open, 0 = Closed. 
            $table->dateTime('completed_at', 0);
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
        Schema::dropIfExists('quizzresponses');
    }
}
