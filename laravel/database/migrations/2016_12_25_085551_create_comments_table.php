<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
          $table->increments('id');
          $table->string('comment_data');
          $table->integer('post_id')->unsigned();
          $table->integer('user_id')->unsigned();
          $table->timestamps();
          $table->foreign('post_id')->references('id')->on('posts')
          ->onDelete('cascade')
          ->onUpdate('cascade');
          $table->foreign('user_id')->references('id')->on('users')
          ->onDelete('cascade')
          ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
