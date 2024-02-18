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
        Schema::create('company_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')
                ->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')
                ->on('students')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('term_id');
            $table->foreign('term_id')->references('id')
                ->on('terms')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('option_id');
            $table->foreign('option_id')->references('id')
                ->on('options')->onDelete('cascade')->onUpdate('cascade');
            $table->tinyInteger('evaluation')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('company_evaluations');
    }
};
