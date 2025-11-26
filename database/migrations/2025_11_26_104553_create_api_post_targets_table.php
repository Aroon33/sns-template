<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_post_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_post_id')
                  ->constrained('api_posts')
                  ->onDelete('cascade');

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->string('status', 20)->default('pending'); // pending / sent / failed
            $table->json('response_json')->nullable();        // APIのレスポンス保存用

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_post_targets');
    }
};

