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
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('travel_id');
            $table->string('passenger_name');
            $table->string('travel_class');
            $table->string('payment_method');
            $table->string('payment_token')->unique();
            $table->boolean('validated');
            $table->unsignedBigInteger('boarding_station');
            $table->unsignedBigInteger('landing_station');
            $table->float('price');
            $table->timestamps();

            $table->foreign('travel_id')->references('id')->on('travels')->onDelete('cascade');
            $table->foreign('boarding_station')->references('id')->on('stations')->onDelete('cascade');
            $table->foreign('landing_station')->references('id')->on('stations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropForeign('tickets_boarding_station_foreign');
        $table->dropForeign('tickets_landing_station_foreign');
        Schema::dropIfExists('tickets');
    }
};
