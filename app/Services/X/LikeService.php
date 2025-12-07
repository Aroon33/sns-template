<?php

namespace App\Services\X;

class LikeService
{
    protected $api;

    public function __construct(XApiClient $api)
    {
        $this->api = $api;
    }

    public function like($userId, $tweetId)
    {
        return $this->api->post("/users/$userId/likes", [
            "tweet_id" => $tweetId
        ]);
    }
}
