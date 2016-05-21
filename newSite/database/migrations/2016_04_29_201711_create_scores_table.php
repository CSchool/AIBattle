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

            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();

            $table->integer('round_id')->index()->unsigned();
            $table->integer('strategy_id')->index()->unsigned();
            $table->integer('score');

            $table->unique(['round_id', 'strategy_id']);

            $table->foreign('round_id')->references('id')->on('rounds')->onDelete('cascade');
            $table->foreign('strategy_id')->references('id')->on('strategies')->onDelete('cascade');

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
