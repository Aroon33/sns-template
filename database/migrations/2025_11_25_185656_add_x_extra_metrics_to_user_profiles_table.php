<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('user_profiles', 'tweet_count')) {
                $table->unsignedBigInteger('tweet_count')->default(0)->after('followers_count');
            }
            if (!Schema::hasColumn('user_profiles', 'following_count')) {
                $table->unsignedBigInteger('following_count')->default(0)->after('tweet_count');
            }
            if (!Schema::hasColumn('user_profiles', 'listed_count')) {
                $table->unsignedBigInteger('listed_count')->default(0)->after('following_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            foreach (['tweet_count','following_count','listed_count'] as $col) {
                if (Schema::hasColumn('user_profiles', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

