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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('group_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('customer')->default(false);
            $table->boolean('supplier')->default(false);
            $table->boolean('employee')->default(false);
            $table->longText('address')->nullable();
            $table->string('city')->nullable();
            $table->string('code_post')->nullable();
            $table->string('code_contact')->nullable();
            $table->string('nik')->nullable();
            $table->string('profession')->nullable();
            $table->string('website')->nullable();
            $table->boolean('active')->default(true);
            $table->foreign('group_id')->references('id')->on('groups');
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
        Schema::dropIfExists('contacts');
    }
};
