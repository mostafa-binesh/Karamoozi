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
        // https://medium.com/@kiasaty/how-to-avoid-enum-data-type-in-laravel-eloquent-1c37ec908773
        Schema::create('enum', function (Blueprint $table) {
            $table->bigIncrements('id'); // permission id
            $table->enum('difficulty', ['easy', 'hard']);
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
