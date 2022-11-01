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
        Schema::create('students', function (Blueprint $table) {
            // FIX: some of these fields should be unique
            // These commented attributes are available in user table
            // $table->string('first_name');
            // $table->string('last_name');
            // $table->id('student_number');
            // $table->string('national_code',10);
            // $table->string('phone_number',11);
            // $table->string('email');
            $table->id();
            $table->string('student_number')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')
            ->on('users')->onDelete('cascade'); 
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->foreign('supervisor_id')->references('employee_id')
            ->on('employees')->onDelete('cascade'); 
            $table->float('grade')->nullable(); // FIX: there is no value for it in pre-reg
            $table->unsignedTinyInteger('passed_units')->nullable(); // FIX: there is no value for it in pre-reg
            $table->bigInteger('faculty_id')->unsigned()->nullable();
            $table->foreign('faculty_id')->references('id')
            ->on('university_faculties')->onDelete('cascade');
            $table->bigInteger('professor_id')->unsigned()->nullable();
            $table->foreign('professor_id')->references('employee_id')
            ->on('employees')->onDelete('cascade');
            $table->unsignedInteger('intership_year')->nullable();
            $table->unsignedInteger('intership_type')->nullable();
            $table->bigInteger('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')
            ->on('companies')->onDelete('cascade');
            $table->boolean('verified')->nullable();
            // $table->boolean('done_pre_registration')->default(false);
            // user auth added fields
            $table->timestamp('email_verified_at')->nullable();
            // $table->uui
            // $table->rememberToken();
            $table->timestamps();
            // FIX: change compayn

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
