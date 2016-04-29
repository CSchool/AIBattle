<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rounds', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('game')->index()->unsigned();
            $table->integer('tournament')->unsigned();
            $table->integer('checkers')->unsigned();

            $table->integer('previousRound')->default(-1);
            $table->string('name', 64);
            $table->dateTime('date');

            $table->boolean('visible')->default(false);
            $table->integer('seed')->default(false);

            $table->foreign('game')->references('id')->on('games')->onDelete('cascade');
            $table->foreign('tournament')->references('id')->on('tournaments')->onDelete('cascade');
            $table->foreign('checkers')->references('id')->on('checkers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rounds');
    }
}
