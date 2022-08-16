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
        Schema::create('payment_receive_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_receive_id');
            $table->unsignedBigInteger('invoice_id');
            $table->bigInteger('pay');
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
        Schema::dropIfExists('payment_receive_details');
    }
};
