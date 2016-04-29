<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();

            $table->integer('round')->index()->unsigned();
            $table->integer('strategy')->index()->unsigned();
            $table->integer('score');

            $table->unique(['round', 'strategy']);

            $table->foreign('round')->references('id')->on('rounds')->onDelete('cascade');
            $table->foreign('strategy')->references('id')->on('strategies')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('scores');
    }
}
