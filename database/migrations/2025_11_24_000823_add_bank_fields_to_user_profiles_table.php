<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            // 銀行情報
            if (!Schema::hasColumn('user_profiles', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('bio');
            }
            if (!Schema::hasColumn('user_profiles', 'bank_branch')) {
                $table->string('bank_branch')->nullable()->after('bank_name');
            }
            if (!Schema::hasColumn('user_profiles', 'bank_account_type')) {
                $table->string('bank_account_type')->nullable()->after('bank_branch'); // 普通/当座など
            }
            if (!Schema::hasColumn('user_profiles', 'bank_account_number')) {
                $table->string('bank_account_number')->nullable()->after('bank_account_type');
            }
            if (!Schema::hasColumn('user_profiles', 'bank_account_holder')) {
                $table->string('bank_account_holder')->nullable()->after('bank_account_number');
            }

            // 報酬
            if (!Schema::hasColumn('user_profiles', 'reward_total')) {
                $table->decimal('reward_total', 14, 2)->default(0)->after('bank_account_holder'); // 累計報酬
            }
            if (!Schema::hasColumn('user_profiles', 'reward_this_month')) {
                $table->decimal('reward_this_month', 14, 2)->default(0)->after('reward_total'); // 今月報酬
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            foreach ([
                'bank_name',
                'bank_branch',
                'bank_account_type',
                'bank_account_number',
                'bank_account_holder',
                'reward_total',
                'reward_this_month',
            ] as $col) {
                if (Schema::hasColumn('user_profiles', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

