<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStrategiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('strategies', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();

            $table->integer('user')->index()->unsigned();
            $table->integer('game')->index()->unsigned();
            $table->integer('tournament')->unsigned();
            
            $table->string('status', 3)->default('OK');
            $table->string('language', 8);

            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('game')->references('id')->on('games')->onDelete('cascade');
            $table->foreign('tournament')->references('id')->on('tournaments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('strategies');
    }
}
