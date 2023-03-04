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
            $table->integer('entrance_year')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('supervisor_id')->nullable(); // this is for industry supervisor
            // ! manzoor az grade, degree (maghta' tahisilie :) )
            // $table->float('grade')->nullable(); 
            $table->unsignedInteger('grade')->nullable(); 
            // ! FIX: there is no value for grade in pre-reg
            $table->unsignedTinyInteger('passed_units')->nullable();
            $table->bigInteger('faculty_id')->unsigned()->nullable();
            $table->foreign('faculty_id')->references('id')
                ->on('university_faculties')->onDelete('cascade');
            $table->bigInteger('professor_id')->unsigned()->nullable();
            $table->foreign('professor_id')->references('id')
                ->on('employees')->onDelete('cascade');
            $table->unsignedTinyInteger('semester')->nullable();
            $table->unsignedInteger('internship_year')->nullable();
            $table->unsignedInteger('internship_type')->nullable();
            $table->bigInteger('company_id')->unsigned()->nullable(); // shows the submitted company for this student
            $table->foreign('company_id')->references('id')
                ->on('companies')->onDelete('cascade');
            $table->unsignedTinyInteger('verified')->default(0); // first step of verification, verified by someone like dorosti
            $table->boolean('pre_reg_done')->default(0);
            $table->boolean('faculty_verified')->default(0); // fourth step of verification before being able to do anything, faculty approval
            // first stage | academic status page on figma | pre-internship | something between starting of pre-reg 
            // and internship

            // ! admin controllers
            $table->unsignedTinyInteger('pre_reg_verified')->default(0);
            $table->string('init_reg_rejection_reason')->nullable();
            $table->string('pre_reg_rejection_reason')->nullable();

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
            $table->unsignedTinyInteger('evaluations_verified')->default(0);

            
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
