<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->nullable(); // id merchant
            $table->integer('worker_id')->unsigned()->nullable(); // id merchant
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('cascade');


            $table->string('body');
            $table->string('title');
            $table->string('data_backend');

            $table->string('image')->nullable();
            $table->string('date');
            $table->string('is_read');


            $table->string('link')->nullable();
            $table->string('platform')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
