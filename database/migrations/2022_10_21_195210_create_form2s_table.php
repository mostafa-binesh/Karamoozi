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
            // approvals
            $table->unsignedTinyInteger("verified")->default(0);
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
