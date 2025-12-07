<?php

namespace App\Services\X;

use Illuminate\Support\Facades\Http;

class XApiClient
{
    protected $token;
    protected $base;

    public function __construct()
    {
        $this->token = config('services.x.bearer_token');
        $this->base  = rtrim(config('services.x.base_uri'), '/');
    }

    public function get($endpoint)
    {
        return Http::withToken($this->token)->get($this->base.$endpoint);
    }

    public function post($endpoint, $payload)
    {
        return Http::withToken($this->token)->post($this->base.$endpoint, $payload);
    }
}
