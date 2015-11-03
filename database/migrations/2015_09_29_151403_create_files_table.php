<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files',function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id');
            $table->string('directory');
            $table->string('file_name')->unique();
            $table->string('thumbnail')->unique();
            $table->string('original_name');
            $table->string('extension');
            $table->integer('size');
            $table->timestamps();
        });
        Schema::create('posts_files',function(Blueprint $table){
            $table->increments('id');
            $table->integer('post_id');
            $table->integer('file_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('files');
        Schema::drop('posts_files');
    }
}
