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
        Schema::create('form7s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign("student_id")->references("id")
                ->on("students")->onDelete("cascade")->onUpdate('cascade');
            $table->unsignedBigInteger('term_id')->nullable();
            $table->foreign("term_id")->references("id")
                ->on("terms")->onDelete("cascade")->onUpdate('cascade');
            $table->string('letter_date')->nullable();
            $table->string('letter_number')->nullable();
            $table->unsignedTinyInteger('verify_industry_collage')->nullable();
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
        Schema::dropIfExists('form7s');
    }
};
