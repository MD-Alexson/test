<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Projects extends Migration
{

    public function up()
    {
        Schema::create('projects',
            function (Blueprint $table) {
            $table->string('domain');
            $table->string('remote_domain')->unique()->nullable();
            $table->integer('user_id');
            $table->integer('main_page_post_id')->nullable();

            $table->string('name');
            $table->string('header_text')->nullable();
            $table->string('vk')->nullable();
            $table->string('fb')->nullable();
            $table->string('tw')->nullable();
            $table->string('yt')->nullable();
            $table->string('insta')->nullable();
            $table->string('blog')->nullable();
            $table->string('image')->nullable();

            $table->text('login_html')->nullable();
            $table->text('deactivated_html')->nullable();
            $table->text('dashboard_html')->nullable();
            $table->text('custom_copyright')->nullable();
            $table->text('sidebar_html')->nullable();
            $table->text('body_start_user_code')->nullable();
            $table->text('head_end_user_code')->nullable();

            $table->boolean('header_dim')->default(true);
            $table->boolean('dashboard_type')->default(true);
            $table->boolean('disable_copyright')->default(false);
            $table->boolean('sidebar')->default(false);
            $table->boolean('main_page_type')->default(false);
            $table->boolean('hide_content')->default(false);

            $table->timestamps();

            $table->primary('domain');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('projects');
    }
}