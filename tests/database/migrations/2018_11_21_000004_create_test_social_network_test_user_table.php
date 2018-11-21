<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestSocialNetworkTestUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_social_network_test_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('test_user_id');
            $table->unsignedInteger('test_social_network_id');
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
        Schema::drop('test_social_network_test_user');
    }
}
