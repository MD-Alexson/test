<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Homeworks extends Migration
{

    public function up()
    {
        Schema::create('homeworks',
            function (Blueprint $table) {
            $table->increments('id');
            $table->string('project_domain');
            $table->integer('post_id');
            $table->integer('suser_id');

            $table->text('text', 1024);
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->boolean('checked')->default(false);

            $table->unique(['post_id', 'suser_id']);

            $table->timestamps();

            $table->foreign('project_domain')->references('domain')->on('projects')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('suser_id')->references('id')->on('susers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('homeworks');
    }
}