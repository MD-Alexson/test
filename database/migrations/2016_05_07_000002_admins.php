<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Admins extends Migration
{

    public function up()
    {
        Schema::create('admins',
            function (Blueprint $table) {
            $table->increments('id');
            $table->string('password');
            $table->rememberToken();
        });
    }

    public function down()
    {
        Schema::drop('admins');
    }
}