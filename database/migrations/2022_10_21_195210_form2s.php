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
        // this form has to be completed by a company's operator / boss
        Schema::create('Form2s', function (Blueprint $table) {
           $table->id();
           $table->unsignedBigInteger('student_id');
           $table->foreign("student_id")->references("id")
           ->on("students")->onDelete("cascade");
           $table->date("start_date");
           $table->string("saturday");
           $table->string("sunday");
           $table->string("monday");
           $table->string("tuesday");
           $table->string("wednesday");
           $table->string("thursday");
           $table->unsignedBigInteger('form_report_id');
           $table->foreign("form_report_id")->references("id")
           ->on("FormatReports")->onDelete("cascade");
           $table->boolean("company_approval");
           $table->boolean("supervisor_approval");
           $table->boolean("university_approval");
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
