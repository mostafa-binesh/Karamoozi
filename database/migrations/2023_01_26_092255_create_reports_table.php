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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            // TODO: remove form_reports and weeklyReports tables
            
            // !notes:
            // report have two types
            // first type is when ind. supervisor submits it when is submitting form2s (submitting new student form) | report_type = 1
            // -- when it's first type report, we have form2_id and don't have student_id 
            // second type is when student wanna send a report about his/her internship | report_type = 2
            // -- when it's first type report, we have student_id and don't have form2_id 
            // $table->unsignedSmallInteger('report_type');  // ! it's not necessary
            
            $table->unsignedBigInteger('form2_id')->nullable();
            $table->foreign('form2_id')->references('id')
                ->on('form2s')->onDelete('cascade');
            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('student_id')->references('id')
                ->on('students')->onDelete('cascade');
            $table->date("date");
            $table->text("description");
            $table->boolean('verified');
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
        Schema::dropIfExists('reports');
    }
};
