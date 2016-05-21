<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrivatemessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('privatemessages', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();

            $table->integer('sender')->unsigned();
            $table->integer('recevier')->unsigned();

            $table->text('title');
            $table->text('text');

            $table->dateTime('date');
            $table->boolean('viewed');

            $table->foreign('sender')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recevier')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('privatemessages');
    }
}
