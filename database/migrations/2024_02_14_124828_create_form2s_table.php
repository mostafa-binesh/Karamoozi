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
        Schema::create('form2s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('industry_supervisor_id')->nullable();
            $table->foreign("industry_supervisor_id")->references("id")
                ->on("industry_supervisors")->onDelete("cascade")->onUpdate('cascade');
            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign("student_id")->references("id")
                ->on("students")->onDelete("cascade")->onUpdate('cascade');
            $table->string('introduction_letter_number');
            $table->date('introduction_letter_date');
            $table->string('internship_department');
            $table->string('supervisor_position');
            $table->date("internship_started_at");
            $table->string('internship_website')->nullable();
            $table->json('schedule_table');
            $table->text('description')->nullable();
            $table->string('rejection_reason')->nullable();
            // approvals
            $table->unsignedTinyInteger("verified")->default(1);
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
        Schema::dropIfExists('form2s');
    }
};
