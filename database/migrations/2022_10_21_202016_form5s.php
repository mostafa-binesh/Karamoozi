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
        Schema::create('From5s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_number');
            $table->foreign("student_number")->references("id")
                ->on("students")->onDelete("cascade");
            $table->boolean("student_approval");
            $table->boolean("supervisor_approval");
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
