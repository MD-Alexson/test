<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PostsLevels extends Migration
{

    public function up()
    {
        Schema::create('posts_levels',
            function (Blueprint $table) {
            $table->integer('post_id');
            $table->integer('level_id');

            $table->primary(['post_id', 'level_id']);

            $table->foreign('post_id')->references('id')->on('posts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('level_id')->references('id')->on('levels')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('posts_levels');
    }
}