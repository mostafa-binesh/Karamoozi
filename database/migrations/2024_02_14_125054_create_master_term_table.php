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
        Schema::create('master_term', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_id')->nullable();
            $table->foreign("master_id")->references("id")
                ->on("employees")->onDelete("cascade")->onUpdate('cascade');
            $table->unsignedBigInteger('term_id')->nullable();
            $table->foreign("term_id")->references("id")
                ->on("terms")->onDelete("cascade")->onUpdate('cascade');
            $table->integer('students_count');
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
        Schema::dropIfExists('master_term');
    }
};
