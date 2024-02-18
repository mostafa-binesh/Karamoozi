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
            $table->id();
            $table->string('student_number');
            $table->integer('entrance_year')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->tinyInteger('grade')->nullable();
            $table->decimal('score')->nullable();
            $table->unsignedInteger('passed_units')->nullable();
            $table->unsignedBigInteger('faculty_id')->unsigned()->nullable();
            $table->foreign('faculty_id')->references('id')
                ->on('university_faculties')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('professor_id')->unsigned()->nullable(); // ! equals to master_id
            $table->foreign('professor_id')->references('id')
                ->on('employees')->onDelete('cascade')->onUpdate('cascade');
            // $table->unsignedTinyInteger('semester')->nullable(); // ! needs to be replaced by term_id
            // $table->unsignedInteger('internship_year')->nullable();
            $table->unsignedBigInteger('term_id')->nullable();
            $table->foreign('term_id')->references('id')->on('terms')
                ->onDelete('cascade')->onUpdate('cascade');
            // $table->unsignedInteger('internship_type')->nullable();
            $table->bigInteger('company_id')->unsigned()->nullable(); // shows the submitted company for this student
            $table->foreign('company_id')->references('id')
                ->on('companies')->onDelete('cascade');
            // ! verified = init reg
            $table->unsignedTinyInteger('verified')->default(0); // first step of verification, verified by someone like dorosti
            $table->boolean('pre_reg_done')->default(0); // pre registration done by the student
            $table->boolean('faculty_verified')->default(0); // fourth step of verification before being able to do anything, faculty approval
            $table->unsignedTinyInteger('stage')->default(1);
            // first stage | academic status page on figma | pre-internship | something between starting of pre-reg
            // and internship
            // ! admin controllers
            $table->unsignedTinyInteger('pre_reg_verified')->default(0);
            $table->string('init_reg_rejection_reason')->nullable(); // this field controller is verified
            $table->string('pre_reg_rejection_reason')->nullable();
            // $table->boolean('expert_verification')->default(0);
            // $table->boolean('supervisor_in_faculty_verification')->default(0);
            // $table->boolean('internship_master_verification')->default(0);
            // $table->boolean('educational_assistant_verification')->default(0);
            // $table->date('internship_started_at')->nullable();
            // $table->date('internship_finished_at')->nullable();
            $table->unsignedTinyInteger('internship_status')->default(1);
            // second stage
            // $table->text('evaluations')->nullable();
            // $table->unsignedTinyInteger('evaluations_verified')->default(0); // = form3 verified
            // $table->unsignedTinyInteger('form4_verified')->default(0); // = companyEvaluations
            // $table->boolean('supervisor_verification')->default(0);
            $table->boolean('internship_finished')->default(0);
            // user auth added fields
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('final_report_path')->nullable();
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
        Schema::dropIfExists('students');
    }
};
