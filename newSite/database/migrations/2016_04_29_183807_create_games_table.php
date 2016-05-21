<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();

            $table->string('name', 30);
            $table->text('description');
            $table->boolean('hasVisualizer')->default(false);
            $table->integer('timeLimit')->default(1000);
            $table->integer('memoryLimit')->default(64000);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('games');
    }
}
