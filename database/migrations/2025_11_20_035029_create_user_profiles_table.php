<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // X（旧Twitter）関連
            $table->string('x_username')->nullable();     // 表示名 @xxxx
            $table->string('x_user_id')->nullable();      // Xの内部ID（数値または文字列）

            // プロフィール情報
            $table->unsignedInteger('followers_count')->default(0);
            $table->string('location')->nullable();       // 都道府県など
            $table->text('bio')->nullable();              // 自己紹介

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
