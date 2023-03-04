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
        Schema::create('Form3s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign("student_id")->references("id")
           ->on("students")->onDelete("cascade");
           $table->float("grade");
           $table->unsignedTinyInteger('verified')->default(0);
           $table->boolean("professor_approval");
           $table->boolean("company_approval");
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
