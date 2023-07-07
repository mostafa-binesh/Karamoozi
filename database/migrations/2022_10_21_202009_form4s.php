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
        Schema::create('Form4s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_number');
            $table->foreign("student_number")->references("id")
                ->on("students")->onDelete("cascade");
            $table->float("grade");
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')
                ->on('companies')->onDelete('cascade');
            $table->text("caption");
            $table->boolean("student_approval");
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
