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
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->string('gender')->nullable();
            $table->string('phone')->nullable();
            $table->string('referral_code')->unique();
            $table->string('referral_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artists');
    }
};
