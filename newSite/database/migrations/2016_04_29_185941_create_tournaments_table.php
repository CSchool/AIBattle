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

            $table->increments('id');
            $table->timestamps();

            $table->integer('game')->unsigned();
            $table->integer('defaultChecker')->unsigned()->nullable(); // dont forget that we hadn't default checker!
            $table->string('name', 32);
            $table->text('description');
            $table->string('state');

            $table->foreign('game')->references('id')->on('games')->onDelete('cascade');
            $table->foreign('defaultChecker')->references('id')->on('checkers')->onDelete('set null');

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
