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
        Schema::create('Demands', function (Blueprint $table) {
            $table->id();
            $table->date("demand_date");
            $table->integer("demand_number");
            $table->unsignedBigInteger("company_id");
            $table->foreign('company_id')->references('id')
            ->on('companies')->onDelete('cascade');
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
