<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->string('heading')->nullable();
            $table->text('body')->nullable();
            $table->string('image_path')->nullable();
            $table->string('hashtags')->nullable();
            $table->timestamps();

            $table->foreign('campaign_id')
                ->references('id')
                ->on('campaigns')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_posts');
    }
};
