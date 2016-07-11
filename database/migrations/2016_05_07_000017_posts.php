<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Posts extends Migration
{

    public function up()
    {
        Schema::create('posts',
            function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');

            $table->string('name');
            $table->text('excerpt')->nullable();
            $table->text('post_html')->nullable();

            $table->string('image')->nullable();
            $table->boolean('header_dim')->default(true);
            $table->string('thumbnail_128')->default("");
            $table->string('thumbnail_750')->default("");
            $table->boolean('thumbnail_size')->default(false);

            $table->double('rating')->default(0.0);
            $table->integer('rates')->default(0);

            $table->text('embed')->nullable();

            $table->boolean('video_download')->default(false);

            $table->boolean('comments_enabled')->default(false);
            $table->boolean('comments_moderate')->default(true);

            $table->boolean('rating_enabled')->default(false);

            $table->boolean('homework_enabled')->default(false);
            $table->boolean('homework_check')->default(false);
            $table->text('homework')->nullable();
            $table->text('homework_required')->nullable();

            $table->string('status')->default("published");
            $table->boolean('comingsoon')->default(false);
            $table->integer('scheduled')->nullable();
            $table->smallInteger('sch2num')->default(1);
            $table->string('sch2type')->default('day');
            $table->string('sch2typename')->default('дней');

            $table->smallInteger('order')->default(1);
            $table->smallInteger('order_all')->default(1);

            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('projects',
            function(Blueprint $table) {
            $table->foreign('main_page_post_id')->references('id')->on('posts')->onUpdate('cascade')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('projects',
            function(Blueprint $table) {
            $table->dropForeign('projects_main_page_post_id_foreign');
        });
        Schema::drop('posts');
    }
}