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
        Schema::create('invoice_sales', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('code', 8);
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('group_id');
            $table->bigInteger('total');
            $table->enum('status', [0, 1])->default(1);
            $table->foreign('order_id')->references('id')->on('order_sales')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
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
        Schema::dropIfExists('invoice_sales');
    }
};
