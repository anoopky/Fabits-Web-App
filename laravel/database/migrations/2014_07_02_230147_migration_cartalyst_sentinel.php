<?php
    
/**
 * Part of the Sentinel package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Sentinel
 * @version    2.0.13
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2016, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MigrationCartalystSentinel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('code');
            $table->boolean('completed')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
        });

        Schema::create('persistences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('code');
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->unique('code');
        });

        Schema::create('reminders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('code');
            $table->boolean('completed')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('name');
            $table->text('permissions')->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->unique('slug');
        });

        Schema::create('role_users', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->nullableTimestamps();

            $table->engine = 'InnoDB';
            $table->primary(['user_id', 'role_id']);
        });

        Schema::create('throttle', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('type');
            $table->string('ip')->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->index('user_id');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 20);
            $table->string('password');
            $table->string('name',40);
            $table->integer('university_id');
            $table->string('wall_picture_big')->default('fabits/default_wall_big');
            $table->string('wall_picture_small')->default('fabits/default_wall_small');
            $table->string('profile_picture_big');
            $table->string('profile_picture_small');
            $table->smallInteger('college_year');
            $table->integer('college_id');
            $table->integer('college_name_id');
            $table->integer('branch_id');
            $table->tinyInteger('gender')->nullable();
            $table->bigInteger('phone')->nullable();
            $table->string('intro')->nullable();
            $table->string('hometown')->nullable();
            $table->tinyInteger('birth_month')->nullable();
            $table->smallInteger('birth_year')->nullable();
            $table->tinyInteger('birth_day')->nullable();
            $table->integer('relationship_id')->unsigned()->nullable();
            $table->integer('last_notification')->unsigned()->default(0);
            $table->tinyInteger('status')->nullable();
            $table->text('permissions')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->unique('username');
            $table->unique('college_id');
            $table->unique('university_id');
            $table->unique('phone');

            $table->foreign('college_name_id')->references('id')->on('college_names')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('relationship_id')->references('id')->on('relationships')
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
        Schema::drop('activations');
        Schema::drop('persistences');
        Schema::drop('reminders');
        Schema::drop('roles');
        Schema::drop('role_users');
        Schema::drop('throttle');
        Schema::drop('users');
    }
}
