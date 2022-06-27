<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subCategory_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('worker_id');
            $table->tinyInteger('time_type')->default(1)->comment('1 => حالا , 2=< لاحقا');
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->date('date')->nullable();
            $table->tinyInteger('is_completed')->default(0);
            $table->tinyInteger('type')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('subCategory_id')->references('id')->on('sub_categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
