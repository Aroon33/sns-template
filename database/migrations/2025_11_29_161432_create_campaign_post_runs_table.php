<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_post_runs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_post_id');
            $table->unsignedBigInteger('user_id');

            $table->string('tweet_id')->nullable();
            $table->string('status')->default('pending');
            $table->json('response_json')->nullable();

            $table->integer('likes')->nullable();
            $table->integer('retweets')->nullable();
            $table->integer('replies')->nullable();
            $table->integer('impressions')->nullable();

            $table->timestamps();

            $table->foreign('campaign_post_id')
                ->references('id')
                ->on('campaign_posts')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_post_runs');
    }
};
