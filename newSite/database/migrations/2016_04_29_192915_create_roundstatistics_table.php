<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoundstatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roundstatistics', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('round')->unsigned();
            $table->integer('user')->unsigned();
            $table->integer('totalScore');

            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('round')->references('id')->on('rounds')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('roundstatistics');
    }
}
