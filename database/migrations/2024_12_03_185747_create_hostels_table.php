<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hostels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('hostel_id')->length(6);
            $table->string('phone')->length(20)->unique()->index();
            $table->string('email')->length(55)->unique()->index();
            $table->string('password')->length(255);
            $table->string('image')->default('public/uploads/default/user.png');
            $table->integer('district_id')->length(2)->nullable()->index();
            $table->string('area_id')->length(4)->nullable()->index();
            $table->string('address')->nullable();
            $table->integer('verify')->length(8);
            $table->integer('forgot')->length(8)->nullable();
            $table->tinyInteger('agree');
            $table->tinyInteger('status')->default('1')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hostels');
    }
};
