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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained();
            $table->foreignId('category_id')->references('id')->on('categories_products')->onDelete('cascade');
            $table->foreignId('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->string('name', 30);
            $table->bigInteger('price_buy')->default(0);
            $table->bigInteger('price_sell')->default(0);
            $table->enum('status', [0, 1])->default(1);
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
        Schema::dropIfExists('products');
    }
};
