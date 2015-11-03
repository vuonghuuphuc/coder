<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users',function(Blueprint $table){
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('location')->nullable();
            $table->string('remember_token')->nullable();
            $table->boolean('active');
            $table->string('active_hash')->nullable();
            $table->dateTime('active_at')->nullable();
            $table->string('recover_hash')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('country_code')->nullable();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();
            $table->string('region')->nullable();
            $table->string('region_name')->nullable();
            $table->string('timezone')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('google_id')->nullable();
            $table->string('linkedin_id')->nullable();
            $table->boolean('ban');
            $table->dateTime('ban_at')->nullable();
            $table->string('ban_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
