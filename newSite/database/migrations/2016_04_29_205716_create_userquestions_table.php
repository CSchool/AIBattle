<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserquestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userquestions', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();

            $table->integer('user');
            $table->text('question');
            $table->text('answer');
            $table->string('status', 32);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('userquestions');
    }
}
