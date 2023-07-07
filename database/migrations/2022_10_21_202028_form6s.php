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
        Schema::create('Form6', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_number');
            $table->foreign("student_number")->references("id")
                ->on("students")->onDelete("cascade");
            $table->boolean("student_approval");
            $table->boolean("supervisor_approval");
            $table->boolean("university_supervisor_approval");
            $table->string("file_url");
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
