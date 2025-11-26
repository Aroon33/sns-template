<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // 基本情報
            $table->string('name');                       // クライアント名（個人名 or 会社名）
            $table->enum('type', ['individual', 'corp']); // 個人 or 法人
            $table->string('contact_email')->nullable(); // 連絡先メール
            $table->string('contact_tel')->nullable();   // 電話番号

            $table->string('company_name')->nullable();  // 会社名（法人のとき）
            $table->string('department')->nullable();    // 部署名など

            $table->text('description')->nullable();     // 仕事内容・依頼内容の概要

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_profiles');
    }
};

