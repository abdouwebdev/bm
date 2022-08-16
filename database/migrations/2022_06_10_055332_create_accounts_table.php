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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('department_id')->nullable();
            $table->unsignedInteger('sector_id')->nullable();
            $table->unsignedInteger('group_id')->nullable();
            $table->unsignedInteger('member_id')->nullable();
            $table->unsignedInteger('author_id')->nullable();
            $table->unsignedInteger('personal_account_id')->nullable();
            $table->bigInteger('amount');
            $table->string('code');
            $table->bigInteger('beginning_balance')->nullable()->default(0);
            $table->bigInteger('ending_balance')->nullable()->default(0);
            $table->date('date');
            $table->enum('status', [0, 1])->default(1);
            $table->timestamps();
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('sector_id')->references('id')->on('sectors');
            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('author_id')->references('id')->on('users');
            $table->foreign('personal_account_id')->references('id')->on('personal_accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
};
