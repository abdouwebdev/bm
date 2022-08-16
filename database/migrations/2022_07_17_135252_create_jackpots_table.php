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
        Schema::create('jackpots', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('group_id')->nullable();
            $table->unsignedInteger('member_id')->nullable();
            $table->string('name');
            $table->string('code');
            $table->integer('amount');
            $table->integer('solde')->nullable()->default(0);
            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('member_id')->references('id')->on('members');
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
        Schema::dropIfExists('jackpots');
    }
};
