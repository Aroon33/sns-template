<?php

namespace App\Services\X;

use Illuminate\Support\Facades\Http;

class TweetService
{
    protected $token;
    protected $base;

    public function __construct()
    {
        $this->token = config('services.x.bearer_token');
        $this->base  = rtrim(config('services.x.base_uri', 'https://api.x.com/2'), '/');

        if (!$this->token) {
            throw new \Exception("X_BEARER_TOKEN が設定されていません");
        }
    }

    /**
     * 投稿処理
     */
    public function tweet(string $text): array
    {
        $endpoint = "{$this->base}/tweets";

        $payload = [
            'text' => $text,
        ];

        $response = Http::withToken($this->token)
            ->post($endpoint, $payload);

        return $response->json() ?? [
            'error' => true,
            'status' => $response->status(),
            'body' => $response->body(),
        ];
    }
}
