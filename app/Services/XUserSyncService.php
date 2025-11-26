<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XUserSyncService
{
    public function syncUser(User $user): void
    {
        $profile = $user->profile;

        if (! $profile || ! $profile->x_user_id) {
            Log::warning("XUserSync: user {$user->id} has no x_user_id");
            return;
        }

        $xUserId = $profile->x_user_id;

        $token = config('services.x.bearer_token');
        if (! $token) {
            Log.w("XUserSync: no X_BEARER_TOKEN configured");
            return;
        }

        // X API v2 のユーザーエンドポイント例（実際のエンドポイントはXの仕様に合わせて調整）
        $response = Http::withToken($token)
            ->get("https://api.x.com/2/users/{$xUserId}", [
                'user.fields' => 'public_metrics,profile_image_url,location,description',
            ]);

        if (! $response->ok()) {
            Log::warning("XUserSync: failed for user_id={$user->id}, status={$response->status()}", [
                'body' => $response->body(),
            ]);
            return;
        }

        $data = $response->json('data');

        if (! $data) {
            Log::warning("XUserSync: no data for user_id={$user->id}");
            return;
        }

        $metrics = $data['public_metrics'] ?? [];

        $profile->update([
            'display_name'    => $data['name']            ?? $profile->display_name,
            'x_username'      => $data['username']        ?? $profile->x_username,
            'avatar_url'      => $data['profile_image_url'] ?? $profile->avatar_url,
            'location'        => $data['location']        ?? $profile->location,
            'bio'             => $data['description']     ?? $profile->bio,
            'followers_count' => $metrics['followers_count'] ?? $profile->followers_count,
            'tweet_count'     => $metrics['tweet_count']     ?? $profile->tweet_count,
            'following_count' => $metrics['following_count'] ?? $profile->following_count,
            'listed_count'    => $metrics['listed_count']    ?? $profile->listed_count,
        ]);
    }

    /**
     * 全ユーザーを一括で同期（管理者が手動で実行する想定）
     */
    public function syncAllUsers(): void
    {
        User::whereHas('profile', function ($q) {
            $q->whereNotNull('x_user_id');
        })->chunk(50, function ($users) {
            foreach ($users as $user) {
                try {
                    $this->syncUser($user);
                    usleep(300000); // レートリミット対策で0.3秒待つなど
                } catch (\Throwable $e) {
                    Log::error("XUserSync: exception for user {$user->id}", ['e' => $e->getMessage()]);
                }
            }
        });
    }
}
