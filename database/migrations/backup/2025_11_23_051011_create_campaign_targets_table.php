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
            $table->unsignedBigInteger('campaign_id');
            $table->unsignedBigInteger('user_id');

            $table->string('status')->default('pending');
            // pending: 対象仮確定  
            // approved: 承認（このユーザーに配信する）
            // rejected: 対象外

            $table->timestamps();

            $table->unique(['campaign_id', 'user_id']);

            $table->foreign('campaign_id')
                ->references('id')
                ->on('campaigns')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_targets');
    }
};
