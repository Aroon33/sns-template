<?php

namespace App\Services\X;

class ReplyService
{
    protected $api;

    public function __construct(XApiClient $api)
    {
        $this->api = $api;
    }

    public function reply($userId, $tweetId, $body)
    {
        return $this->api->post('/tweets', [
            'text' => $body,
            'reply' => ['in_reply_to_tweet_id' => $tweetId]
        ]);
    }
}
