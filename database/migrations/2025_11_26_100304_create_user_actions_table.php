<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // 種類：投稿依頼・いいね依頼・DMなど
            $table->string('type', 50); // 例: post_request, like_request, dm, other

            // 状態：pending / done など（必要に応じて拡張）
            $table->string('status', 20)->default('pending');

            // 管理者メモ
            $table->text('note')->nullable();

            // 将来用：対象キャンペーンがあれば campaign_id を追加してもOK
            // $table->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_actions');
    }
};

