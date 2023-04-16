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
        Schema::create('FormatReports', function (Blueprint $table) {
            $table->id();
            $table->text("caption");
            $table->date("start_date");
            $table->time("start_time");
            $table->integer("form2_id");
            $table->unsignedBigInteger('student_id');
            $table->foreign("student_id")->references("id")
           ->on("students")->onDelete("cascade");
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
