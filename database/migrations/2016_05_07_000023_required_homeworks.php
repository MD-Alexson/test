<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RequiredHomeworks extends Migration
{

    public function up()
    {
        Schema::create('required_homeworks',
            function (Blueprint $table) {
            $table->integer('post_id');
            $table->integer('required_post_id');

            $table->primary(['post_id', 'required_post_id']);

            $table->foreign('post_id')->references('id')->on('posts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('required_post_id')->references('id')->on('posts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('required_homeworks');
    }
}