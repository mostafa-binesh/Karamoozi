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
        Schema::create('employees', function (Blueprint $table) {
            $table->string('first_name');
            $table->string('last_name');
            $table->id('employee_id');
            $table->string('national_code',10);
            $table->string('phone_number',11);
            $table->string('email',10);
            $table->unsignedBigInteger('faculty_id'); // FIX: should be unsigned
            $table->foreign('faculty_id')->references('id')
            ->on('university_faculties')->onDelete('cascade'); // FIX: add foregin key bigint and table name should be the same
            $table->boolean('verified');
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
