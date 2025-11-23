<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_targets', function (Blueprint $table) {
            $table->id();

            // 案件のID
            $table->foreignId('campaign_id')
                ->constrained()
                ->onDelete('cascade');

            // ユーザーのID
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // ステータス（approved / rejected / pending etc）
            $table->string('status')->default('approved');

            $table->timestamps();

            // 案件 × ユーザー の組み合わせは一意
            $table->unique(['campaign_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_targets');
    }
};

