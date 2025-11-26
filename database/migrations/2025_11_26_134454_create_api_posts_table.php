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
    // すでにテーブルがある場合は一旦何もしない or drop しても良いが、
    // 今回は manual DROP 後に migrate する前提なのでチェックなしでOK
    Schema::create('api_posts', function (Blueprint $table) {
        $table->id();

        // 管理者ユーザーID
        $table->foreignId('created_by')
              ->constrained('users')
              ->onDelete('cascade');

        $table->string('title');            // 管理用タイトル
        $table->text('body');               // 投稿本文
        $table->string('hashtags')->nullable();   // "#aaa #bbb"
        $table->string('image_path')->nullable(); // 画像パス（storage上）

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_posts');
    }
};
