<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            // クライアント案件作成ページ 用
            if (!Schema::hasColumn('campaigns', 'title')) {
                $table->string('title')->nullable()->after('id'); // 案件名
            }
            if (!Schema::hasColumn('campaigns', 'lp_url')) {
                $table->string('lp_url')->nullable()->after('title'); // LP の URL
            }
            if (!Schema::hasColumn('campaigns', 'daily_budget_max')) {
                $table->decimal('daily_budget_max', 12, 2)->nullable()->after('lp_url'); // 1日の最大広告費
            }
            if (!Schema::hasColumn('campaigns', 'desired_post_count')) {
                $table->unsignedInteger('desired_post_count')->nullable()->after('daily_budget_max'); // 希望投稿人数
            }

            // 詳細数字確認ページ 用（集計値として持つ想定）
            if (!Schema::hasColumn('campaigns', 'posts_count')) {
                $table->unsignedInteger('posts_count')->default(0)->after('desired_post_count'); // 投稿数
            }
            if (!Schema::hasColumn('campaigns', 'posters_count')) {
                $table->unsignedInteger('posters_count')->default(0)->after('posts_count'); // 投稿人数
            }
            if (!Schema::hasColumn('campaigns', 'likes_count')) {
                $table->unsignedBigInteger('likes_count')->default(0)->after('posters_count'); // いいね数
            }
            if (!Schema::hasColumn('campaigns', 'retweets_count')) {
                $table->unsignedBigInteger('retweets_count')->default(0)->after('likes_count'); // リツイート数
            }
            if (!Schema::hasColumn('campaigns', 'views_count')) {
                $table->unsignedBigInteger('views_count')->default(0)->after('retweets_count'); // 閲覧数
            }
            if (!Schema::hasColumn('campaigns', 'total_ad_cost')) {
                $table->decimal('total_ad_cost', 14, 2)->default(0)->after('views_count'); // 合計広告金額
            }
            if (!Schema::hasColumn('campaigns', 'today_ad_cost')) {
                $table->decimal('today_ad_cost', 14, 2)->default(0)->after('total_ad_cost'); // 当日の広告金額
            }
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            foreach ([
                'title',
                'lp_url',
                'daily_budget_max',
                'desired_post_count',
                'posts_count',
                'posters_count',
                'likes_count',
                'retweets_count',
                'views_count',
                'total_ad_cost',
                'today_ad_cost',
            ] as $column) {
                if (Schema::hasColumn('campaigns', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

