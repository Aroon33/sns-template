<?php

namespace App\Services\X;

class FollowService
{
    protected $api;

    public function __construct(XApiClient $api)
    {
        $this->api = $api;
    }

    public function follow($userId, $targetId)
    {
        return $this->api->post("/users/$userId/following", [
            "target_user_id" => $targetId
        ]);
    }
}
