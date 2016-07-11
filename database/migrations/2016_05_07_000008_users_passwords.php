<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersPasswords extends Migration
{

    public function up()
    {
        Schema::create('users_passwords',
            function (Blueprint $table) {
            $table->string('key');
            $table->integer('user_id')->unique();
            $table->timestamps();

            $table->primary('key');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('users_passwords');
    }
}