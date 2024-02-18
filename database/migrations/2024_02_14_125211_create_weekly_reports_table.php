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
        Schema::create('weekly_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign("student_id")->references("id")
                ->on("students")->onDelete("cascade")->onUpdate('cascade');
            $table->unsignedBigInteger('term_id')->nullable();
            $table->foreign("term_id")->references("id")
                ->on("terms")->onDelete("cascade")->onUpdate('cascade');
            $table->text('report');
            $table->date('report_date');
            $table->integer('week_number');
            $table->unsignedTinyInteger('status');
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
        Schema::dropIfExists('weekly_reports');
    }
};
