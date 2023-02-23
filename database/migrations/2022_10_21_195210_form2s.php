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
        // this form has to be completed by a company's operator / boss (Industry Supervisor)
        Schema::create('form2s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('industry_supervisor_id')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign("student_id")->references("id")
                ->on("students")->onDelete("cascade");
            //    $table->unsignedBigInteger('industry_supervisor_id');
            //    $table->foreign("student_id")->references("id")
            //    ->on("students")->onDelete("cascade");

            $table->string('introduction_letter_number');
            $table->date('introduction_letter_date');
            $table->string('internship_department');
            $table->string('supervisor_position');
            $table->date("internship_start_date");
            $table->string('internship_website')->nullable();
            $table->json('schedule_table');
            $table->text('description')->nullable();
            $table->boolean("university_approval")->default(false);
            // $table->string("saturday");
            // $table->string("sunday");
            // $table->string("monday");
            // $table->string("tuesday");
            // $table->string("wednesday");
            // $table->string("thursday");


            // $table->unsignedBigInteger('form_report_id');
            // $table->foreign("form_report_id")->references("id")
            //     ->on("FormatReports")->onDelete("cascade");
            $table->boolean("company_approval")->default(false);
            $table->boolean("supervisor_approval")->default(false);
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
        //
    }
};
