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
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
			$table->string('idNo',20)->unique()->nullable();
			$table->string('session',15);
		    $table->integer('department_id')->unsigned()->nullable();
			$table->integer('sector_id')->unsigned()->nullable();
			$table->integer('group_id')->unsigned()->nullable();
			$table->integer('author_id')->unsigned()->nullable();
			$table->string('firstName');
			$table->string('lastName');
			$table->string('gender',10);
			$table->string('bloodgroup',10)->nullable();
			$table->string('nationality',50)->nullable();
			$table->date('dob');
			$table->string('photo',30)->nullable();
			$table->string('age',30)->nullable();
			$table->string('mobileNo',15)->nullable();
			$table->string('fatherName',180)->nullable();
			$table->string('fatherMobileNo',15)->nullable();
			$table->string('motherName',180)->nullable();
			$table->string('motherMobileNo',15)->nullable();
		    $table->string('localGuardian',180)->nullable();
			$table->string('localGuardianMobileNo',15)->nullable();
			$table->string('presentAddress',500)->nullable();
			$table->string('isActive',10)->default('Yes');
			$table->timestamps();
			$table->foreign('department_id')->references('id')->on('departments');
			$table->foreign('sector_id')->references('id')->on('sectors');
			$table->foreign('group_id')->references('id')->on('groups');
			$table->foreign('author_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
};
