<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDuelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('duels', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();

            $table->integer('round')->default(-1)->index();
            $table->integer('strategy1')->index()->unsigned();
            $table->integer('strategy2')->index()->unsigned();
            $table->string('status', 8);

            $table->foreign('strategy1')->references('id')->on('strategies')->onDelete('cascade');
            $table->foreign('strategy2')->references('id')->on('strategies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('duels');
    }
}
