<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('PasswordResetTokens', function (Blueprint $table) {
            $table->string('Email')->primary();
            $table->string('Token');
            $table->timestamp('CreatedAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PasswordResetTokens');
    }
};
