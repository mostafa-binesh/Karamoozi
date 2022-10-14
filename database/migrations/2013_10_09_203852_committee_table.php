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
        //
        Schema::create('committees', function (Blueprint $table) {
            $table->id();
            $table->string('committee_name')->unique();
            $table->string('caption');
            $table->string('image',1024);
            // $table->string('email');
            // $table->string('username');
            // $table->string('password');
            // $table->string('phone_number');
            // $table->string('caption');
            // $table->date('birthday');
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
        Schema::dropIfExists('committees');
    }
};
