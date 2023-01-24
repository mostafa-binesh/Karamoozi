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
        Schema::create('EvaluationCommunications', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id');
            $table->foreign("student_id")->references("id")
            ->on("students")->onDelete("cascade");
            $table->integer("form_id");
            $table->unsignedBigInteger('employee_id');
           $table->foreign('employee_id')->references('id')
            ->on('employees')->onDelete('cascade');
            $table->float("grade");
            $table->id("evaluation_communications_id");
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
