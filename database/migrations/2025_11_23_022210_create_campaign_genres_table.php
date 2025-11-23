<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_genres', function (Blueprint $table) {
            $table->id();

            // campaign_id と genre_id は unsignedBigInteger で明示
            $table->unsignedBigInteger('campaign_id');
            $table->unsignedBigInteger('genre_id');

            $table->timestamps();

            $table->unique(['campaign_id', 'genre_id']);

            // 外部キー定義を明示的に書く
            $table->foreign('campaign_id')
                ->references('id')
                ->on('campaigns')
                ->onDelete('cascade');

            $table->foreign('genre_id')
                ->references('id')
                ->on('genres')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_genres');
    }
};

