<?php

namespace App\Services;

use App\Models\ApiPost;
use App\Models\ApiPostTarget;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XApiService
{
    /**
     * X に 1ユーザー分の投稿を送信する
     */
    public function postForTarget(ApiPost $post, ApiPostTarget $target): array
    {
        $bearer = config('services.x.bearer_token');
        $base   = rtrim(config('services.x.base_uri', 'https://api.x.com/2'), '/');

        if (! $bearer) {
            throw new \RuntimeException('X_BEARER_TOKEN が設定されていません。');
        }

        // 投稿本文を組み立て
        $text = $post->body;

        // 対象ユーザーをメンションしたい場合
        $username = $target->user?->profile?->x_username;
        if ($username) {
            $text .= "\n\n@" . ltrim($username, '@');
        }

        // ハッシュタグ
        if (!empty($post->hashtags)) {
            $text .= "\n\n" . $post->hashtags;
        }

        $endpoint = $base . '/tweets';

        $payload = [
            'text' => $text,
            // 画像を使う場合は media_id をここに追加する
        ];

        $response = Http::withToken($bearer)->post($endpoint, $payload);

        if (! $response->successful()) {
            Log::warning('X API tweet failed', [
                'target_id' => $target->id,
                'status'    => $response->status(),
                'body'      => $response->body(),
            ]);

            return [
                'success' => false,
                'status'  => $response->status(),
                'body'    => $response->json(),
            ];
        }

        return [
            'success' => true,
            'status'  => $response->status(),
            'body'    => $response->json(),
        ];
    }
}
