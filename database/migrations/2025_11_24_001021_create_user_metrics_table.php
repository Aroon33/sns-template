<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('followers_count')->default(0);
            $table->unsignedBigInteger('posts_count')->default(0);
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->unsignedBigInteger('retweets_count')->default(0);
            $table->unsignedBigInteger('impressions_count')->default(0);

            $table->timestamp('last_synced_at')->nullable(); // API取得日時
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_metrics');
    }
};

