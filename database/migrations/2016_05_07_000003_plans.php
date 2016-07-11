<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Plans extends Migration
{

    public function up()
    {
        Schema::create('plans',
            function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->smallInteger('projects');
            $table->integer('susers');
            $table->smallInteger('space');
        });
    }

    public function down()
    {
        Schema::drop('plans');
    }
}