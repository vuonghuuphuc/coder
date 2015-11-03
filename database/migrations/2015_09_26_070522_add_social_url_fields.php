<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSocialUrlFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('facebook_url')->nullable();
            $table->string('google_url')->nullable();
            $table->string('linkedin_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('facebook_url');
            $table->dropColumn('google_url');
            $table->dropColumn('linkedin_url');
        });
    }
}
