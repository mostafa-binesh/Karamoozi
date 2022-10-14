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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            // $table->string('username');
            $table->string('email')->unique(); 
            $table->string('phone_number')->unique(); 
            $table->bigInteger('committees')->unsigned()->nullable(); 
            $table->foreign('committees')->references('id')->on('committees');
            $table->string('caption')->nullable();
            $table->string('phone_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('temp_password')->nullable();
            $table->date('birthday');
            $table->string('kind_of_help')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('email')->unique();
        //     $table->timestamp('email_verified_at')->nullable();
        //     $table->string('password');
        //     $table->rememberToken();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
