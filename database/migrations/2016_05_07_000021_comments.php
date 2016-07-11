<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Comments extends Migration
{

    public function up()
    {
        Schema::create('comments',
            function (Blueprint $table) {
            $table->increments('id');
            $table->string('project_domain');
            $table->integer('post_id');
            $table->integer('commentable_id');
            $table->string('commentable_type');
            $table->boolean('allowed')->default(false);
            $table->string('text', 1024);

            $table->timestamps();

            $table->foreign('project_domain')->references('domain')->on('projects')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('comments');
    }
}