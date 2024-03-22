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
            $table->decimal('company_grade')->default(0);
            $table->unsignedBigInteger('company_boss_id');
            $table->foreign('company_boss_id')->references('id')
                ->on('industry_supervisors')->onDelete('cascade')->onUpdate('cascade');
            $table->string('company_number', 11);
            $table->string('company_registry_code');
            $table->string('company_phone', 11);
            $table->string('company_address')->nullable();
            $table->string('company_category')->nullable();
            $table->string('company_postal_code')->nullable();
            // $table->boolean('company_is_registered')->nullable();
            $table->tinyInteger('company_type');
            $table->boolean('verified');
            $table->string('image');
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
        Schema::dropIfExists('table_companies');
    }
};
