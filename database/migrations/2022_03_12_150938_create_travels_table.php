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
        Schema::create('travels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('arrival_statoin');
            $table->unsignedBigInteger('departure_station');
            $table->timestamp('departure_time');
            $table->float('distance');
            $table->float('estimated_duratoin');
            $table->text('description');
            $table->string('status');
            $table->timestamps();

            $table->foreign('arrival_statoin')->references('id')->on('stations')->onDelete('cascade');
            $table->foreign('departure_station')->references('id')->on('stations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('travels');
    }
};
