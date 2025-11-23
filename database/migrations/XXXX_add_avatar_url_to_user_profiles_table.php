<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('user_profiles', 'avatar_url')) {
                $table->string('avatar_url')->nullable()->after('bio');
                // 位置を変えたいなら after('x_user_id') などにしてOK
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('user_profiles', 'avatar_url')) {
                $table->dropColumn('avatar_url');
            }
        });
    }
};
