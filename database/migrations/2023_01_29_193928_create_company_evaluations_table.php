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
        // we have two types of company evaluations
        // -- first one, has option id and evaluation and doesn't have description
        // --- second one, only has description
        Schema::create('company_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')
                ->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')
                ->on('students');
            $table->unsignedBigInteger('option_id')->nullable();
            $table->foreign('option_id')->references('id')
                ->on('options')->onDelete('cascade');
            $table->unsignedTinyInteger('evaluation')->nullable();;
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
