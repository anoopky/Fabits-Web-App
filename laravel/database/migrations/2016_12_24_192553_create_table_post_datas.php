<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePostDatas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_datas', function (Blueprint $table) {
          $table->increments('id');
          $table->text('source');
          $table->string('type');
          $table->string('data')->nullable();
          $table->integer('post_id')->unsigned();
          $table->timestamps();
          $table->foreign('post_id')->references('id')->on('posts')
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
        Schema::dropIfExists('post_datas');
    }
}
