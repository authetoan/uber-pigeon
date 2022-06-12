<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->unsignedBigInteger('user_pigeon_id')->nullable();
            $table->foreign('user_pigeon_id')->references('id')->on('users');
            $table->unsignedBigInteger('user_customer_id');
            $table->foreign('user_customer_id')->references('id')->on('users');
            $table->integer('weight')->nullable();
            $table->integer('distance');
            $table->integer('fee');
            $table->string('destination')->nullable();
            $table->string('destination_latitude');
            $table->string('destination_longitude');
            $table->enum('status',['INITIAL','IN_PROGRESS','FINISHED']);
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
        Schema::dropIfExists('orders');
    }
};
