<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();

            $table->integer('game_id')->unsigned();
            $table->integer('defaultChecker')->unsigned()->nullable();

            $table->string('name', 32);
            $table->text('description');
            $table->string('state');

        });


        Schema::table('tournaments', function ($table) {
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
        });

        Schema::table('tournaments', function ($table) {
            $table->foreign('defaultChecker')->references('id')->on('checkers')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tournaments');
    }
}
