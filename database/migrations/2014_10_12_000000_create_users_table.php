<?php

use App\Models\User;
use App\Providers\GenerateRandomId;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;


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
            $table->string('rand_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username')->unique();
            $table->string('national_code')->unique(); // because some national codes have 0 in the beginning
            $table->string('email')->unique();
            $table->string('phone_number')->unique(); // because phone numbers have 0 in the beginning
            // will handle it with roles, if role!=='student' then user is employee
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
