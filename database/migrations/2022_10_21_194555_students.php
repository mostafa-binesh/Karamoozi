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
            // https://www.figma.com/file/bWf9Ptmonm5qIUBZl4v8tc/%D8%B3%D8%A7%D9%85%D8%A7%D9%86%D9%87-%DA%A9%D8%A7%D8%B1%D8%A7%D9%85%D9%88%D8%B2%DB%8C-%D8%AF%D8%A7%D9%86%D8%B4%DA%AF%D8%A7%D9%87-%D8%B4%D9%87%DB%8C%D8%AF-%D8%B1%D8%AC%D8%A7%DB%8C%DB%8C?node-id=407%3A1527
            // FIX: some of these fields should be unique
            $table->id();
            // $table->bigInteger('student_number')->nullable();
            $table->string('student_number')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('supervisor_id')->nullable();
            // $table->foreign('supervisor_id')->references('employee_id') // QUESTION: does this mean industry supervisor or faculty supervisor?
            // ->on('employees')->onDelete('cascade'); 
            $table->float('grade')->nullable(); // FIX: there is no value for it in pre-reg
            $table->unsignedTinyInteger('passed_units')->nullable(); // FIX: there is no value for it in pre-reg
            $table->bigInteger('faculty_id')->unsigned()->nullable();
            $table->foreign('faculty_id')->references('id')
                ->on('university_faculties')->onDelete('cascade');
            $table->bigInteger('professor_id')->unsigned()->nullable();
            $table->foreign('professor_id')->references('employee_id')
                ->on('employees')->onDelete('cascade');
            $table->unsignedInteger('internship_year')->nullable();
            $table->unsignedInteger('internship_type')->nullable();
            $table->bigInteger('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')
                ->on('companies')->onDelete('cascade');
            $table->boolean('verified')->default(0); // this needs to be replaced with pre-reg-verified
            // first stage | academic status page on figma | pre-internship | something between starting of pre-reg 
            // and internship
            $table->boolean('pre_reg_verified')->default(0);
            $table->boolean('expert_verification')->default(0);
            $table->boolean('supervisor_in_faculty_verification')->default(0);
            $table->boolean('internship_master_verification')->default(0);
            $table->boolean('educational_assistant_verification')->default(0);
            $table->date('internship_started_at')->nullable();
            $table->date('internship_finished_at')->nullable();
            $table->unsignedTinyInteger('internship_status')->default(1);
            // second stage 


            // $table->json('evaluations')->nullable();
            $table->text('evaluations')->nullable();

            
            // start of internship (apprent.) figma

            // $table->boolean('supervisor_submitted')->default(0); // it can be handled in another way 
            $table->boolean('supervisor_verification')->default(0);

            $table->boolean('internship_finished')->default(0);
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
