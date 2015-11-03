<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts',function(Blueprint $table){
            $table->increments('id');
            $table->string('hash_id')->unique();
            $table->integer('user_id');
            $table->string('title');
            $table->string('title_url')->unique();
            $table->mediumText('description')->nullable();
            $table->string('keywords')->nullable();
            $table->longText('body');
            $table->boolean('active');
            $table->dateTime('active_at')->nullable();
            $table->timestamps();
        });
        Schema::create('users_posts',function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('post_id');
        });
        Schema::create('posts_skills',function(Blueprint $table){
            $table->increments('id');
            $table->integer('post_id');
            $table->integer('skill_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('posts');
        Schema::drop('users_posts');
        Schema::drop('posts_skills');
    }
}
