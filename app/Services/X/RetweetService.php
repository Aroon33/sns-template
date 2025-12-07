<?php

namespace App\Services\X;

class RetweetService
{
    protected $api;

    public function __construct(XApiClient $api)
    {
        $this->api = $api;
    }

    public function retweet($userId, $tweetId)
    {
        return $this->api->post("/users/$userId/retweets", [
            "tweet_id" => $tweetId
        ]);
    }
}
