<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')  // 管理者ユーザーID
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->string('title');        // 管理用タイトル
            $table->text('body');           // 投稿本文
            $table->string('hashtags')->nullable(); // "#aaa #bbb" など
            $table->string('image_path')->nullable(); // storage上のパス

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_posts');
    }
};

