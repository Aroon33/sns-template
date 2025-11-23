<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();

            // 将来的に広告主ユーザーを紐づける場合は client_id 等を追加してOK
            // $table->foreignId('client_id')->nullable()->constrained('users');

            $table->string('title');             // 案件タイトル
            $table->text('description')->nullable(); // 案件説明
            $table->text('post_text');           // 投稿本文テンプレ（ハッシュタグ含む）
            $table->string('image_path')->nullable(); // 画像のパス（後で使う）

            $table->unsignedInteger('min_followers')->nullable(); // 対象最低フォロワー
            $table->unsignedInteger('max_followers')->nullable(); // 対象最大フォロワー

            $table->dateTime('start_at')->nullable(); // 開始日時
            $table->dateTime('end_at')->nullable();   // 終了日時

            $table->string('status')->default('draft'); // draft / active / closed 等

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};

