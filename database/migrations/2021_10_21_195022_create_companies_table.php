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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->text('caption')->nullable();
            $table->float("company_grade")->nullable();
            $table->bigInteger("company_boss_id")->unsigned()->nullable();
            $table->string('company_number', 11)->nullable();
            $table->string("company_registry_code")->nullable(); // FIX LATER
            $table->string('company_phone')->nullable();
            $table->string('company_address')->nullable();
            $table->string("company_category")->nullable();
            $table->string("company_postal_code")->nullable();
            $table->boolean("company_is_registered")->nullable();
            $table->unsignedTinyInteger("company_type");
            $table->boolean('verified');
            // TODO: NEED TO KNOW WHICH STUDENT SUBMITTED THIS COMPANY 
            $table->unsignedBigInteger("student_id")->nullable(); // if user has submitted the company
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
        //
    }
};
