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
        Schema::create('form4s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign("student_id")->references("id")
                ->on("students")->onDelete("cascade")->onUpdate('cascade');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign("company_id")->references("id")
                ->on("companies")->onDelete("cascade")->onUpdate('cascade');
            $table->unsignedBigInteger('term_id')->nullable();
            $table->foreign("term_id")->references("id")
                ->on("terms")->onDelete("cascade")->onUpdate('cascade');
            $table->decimal('grade');
            $table->text('caption');
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
        Schema::dropIfExists('form4s');
    }
};
