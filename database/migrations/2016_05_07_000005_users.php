<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Users extends Migration
{

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plan_id');
            $table->string('name', 64);
            $table->string('email', 64)->unique();
            $table->string('password');

            $table->string('phone', 40)->nullable();
            $table->string('site', 128)->nullable();
            $table->string('vk', 128)->nullable();
            $table->string('fb', 128)->nullable();
            $table->string('linkedin', 128)->nullable();

            $table->integer('expires')->default(2147483647);
            $table->boolean('status')->default(true);
            $table->smallInteger('payment_term')->nullable();

            $table->rememberToken();
            $table->timestamps();

            $table->foreign('plan_id')->references('id')->on('plans')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('users');
    }
}