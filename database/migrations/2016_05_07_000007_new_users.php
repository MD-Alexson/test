<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewUsers extends Migration
{

    public function up()
    {
        Schema::create('new_users',
            function (Blueprint $table) {
            $table->string('key');
            $table->integer('user_id')->unique();

            $table->primary('key');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('new_users');
    }
}