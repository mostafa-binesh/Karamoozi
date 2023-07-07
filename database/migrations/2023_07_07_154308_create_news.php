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
        //یک خبر ، عنوان داره متن داره آیدی داره تاریخ ایجاد داره. عکس هم داره
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title',100)->unique();
            $table->text('body');
            $table->string('image',255);
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
        Schema::dropIfExists('news');
    }
};

