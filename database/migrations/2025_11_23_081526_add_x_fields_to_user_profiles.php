<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            // 登録名（漢字）
            if (!Schema::hasColumn('user_profiles', 'display_name')) {
                $table->string('display_name')->nullable()->after('user_id');
            }

            // X の @ユーザー名
            if (!Schema::hasColumn('user_profiles', 'x_username')) {
                $table->string('x_username')->nullable()->after('display_name');
            }

            // X の内部ユーザーID
            if (!Schema::hasColumn('user_profiles', 'x_user_id')) {
                $table->string('x_user_id')->nullable()->after('x_username');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('user_profiles', 'display_name')) {
                $table->dropColumn('display_name');
            }
            if (Schema::hasColumn('user_profiles', 'x_username')) {
                $table->dropColumn('x_username');
            }
            if (Schema::hasColumn('user_profiles', 'x_user_id')) {
                $table->dropColumn('x_user_id');
            }
        });
    }
};


