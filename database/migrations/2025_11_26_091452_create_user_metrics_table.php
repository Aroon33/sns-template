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
    // すでに user_metrics テーブルが存在するなら何もしない
    if (Schema::hasTable('user_metrics')) {
        return;
    }

    Schema::create('user_metrics', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->unsignedBigInteger('followers_count')->default(0);
        $table->unsignedBigInteger('tweet_count')->default(0);
        $table->unsignedBigInteger('following_count')->default(0);
        $table->unsignedBigInteger('listed_count')->default(0);
        $table->timestamp('last_synced_at')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_metrics');
    }
};
