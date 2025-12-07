#!/bin/bash

echo "=== X Actions directories and files generator ==="

BASE="app"
VIEW="resources/views"

# 1. Create directories
echo "Creating directories..."

mkdir -p $BASE/Http/Controllers/Admin
mkdir -p $BASE/Services/X
mkdir -p $VIEW/admin/x_actions/common
mkdir -p $VIEW/admin/x_actions/post
mkdir -p $VIEW/admin/x_actions/like
mkdir -p $VIEW/admin/x_actions/retweet
mkdir -p $VIEW/admin/x_actions/follow
mkdir -p $VIEW/admin/x_actions/reply

echo "Directories created."

# 2. Create Controllers
echo "Creating Controllers..."

cat << 'EOF' > $BASE/Http/Controllers/Admin/XPostController.php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\X\TweetService;

class XPostController extends Controller
{
    public function index()
    {
        // ユーザー選択画面
        return view('admin.x_actions.common.user_select', [
            'action' => 'post'
        ]);
    }

    public function create(Request $request)
    {
        $selectedUsers = $request->user_ids ?? [];
        return view('admin.x_actions.post.create', compact('selectedUsers'));
    }

    public function execute(Request $request, TweetService $tweetService)
    {
        $selectedUsers = $request->user_ids ?? [];
        $heading = $request->heading;
        $body = $request->body;
        $hashtags = $request->hashtags;

        foreach ($selectedUsers as $userId) {
            $tweetService->tweet($userId, $heading, $body, $hashtags);
        }

        return view('admin.x_actions.post.result');
    }
}
EOF

cat << 'EOF' > $BASE/Http/Controllers/Admin/XLikeController.php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\X\LikeService;

class XLikeController extends Controller
{
    public function index()
    {
        return view('admin.x_actions.common.user_select', [
            'action' => 'like'
        ]);
    }

    public function execute(Request $request, LikeService $likeService)
    {
        foreach ($request->user_ids ?? [] as $userId) {
            $likeService->like($userId, $request->tweet_id);
        }
        return "Done";
    }
}
EOF

cat << 'EOF' > $BASE/Http/Controllers/Admin/XRetweetController.php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\X\RetweetService;

class XRetweetController extends Controller
{
    public function index()
    {
        return view('admin.x_actions.common.user_select', [
            'action' => 'retweet'
        ]);
    }

    public function execute(Request $request, RetweetService $rtService)
    {
        foreach ($request->user_ids ?? [] as $userId) {
            $rtService->retweet($userId, $request->tweet_id);
        }
        return "Done";
    }
}
EOF

cat << 'EOF' > $BASE/Http/Controllers/Admin/XFollowController.php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\X\FollowService;

class XFollowController extends Controller
{
    public function index()
    {
        return view('admin.x_actions.common.user_select', [
            'action' => 'follow'
        ]);
    }

    public function execute(Request $request, FollowService $followService)
    {
        foreach ($request->user_ids ?? [] as $userId) {
            $followService->follow($userId, $request->target_id);
        }
        return "Done";
    }
}
EOF

cat << 'EOF' > $BASE/Http/Controllers/Admin/XReplyController.php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\X\ReplyService;

class XReplyController extends Controller
{
    public function index()
    {
        return view('admin.x_actions.common.user_select', [
            'action' => 'reply'
        ]);
    }

    public function create(Request $request)
    {
        return view('admin.x_actions.reply.create', [
            'selectedUsers' => $request->user_ids,
            'tweet_id' => $request->tweet_id
        ]);
    }

    public function execute(Request $request, ReplyService $replyService)
    {
        foreach ($request->user_ids ?? [] as $userId) {
            $replyService->reply($userId, $request->tweet_id, $request->body);
        }
        return "Done";
    }
}
EOF

echo "Controllers created."

# 3. Create Services
echo "Creating Services..."

cat << 'EOF' > $BASE/Services/X/XApiClient.php
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
EOF

cat << 'EOF' > $BASE/Services/X/TweetService.php
<?php

namespace App\Services\X;

class TweetService
{
    protected $api;

    public function __construct(XApiClient $api)
    {
        $this->api = $api;
    }

    public function tweet($userId, $heading, $body, $hashtags)
    {
        $text = trim($heading."\n\n".$body."\n\n".$hashtags);

        return $this->api->post('/tweets', [
            'text' => $text,
        ]);
    }
}
EOF

cat << 'EOF' > $BASE/Services/X/LikeService.php
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
EOF

cat << 'EOF' > $BASE/Services/X/RetweetService.php
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
EOF

cat << 'EOF' > $BASE/Services/X/FollowService.php
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
EOF

cat << 'EOF' > $BASE/Services/X/ReplyService.php
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
EOF

echo "Services created."

# 4. Create basic Views
echo "Creating basic Views..."

cat << 'EOF' > $VIEW/admin/x_actions/common/user_select.blade.php
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ユーザー選択（{{ $action }}）
        </h2>
    </x-slot>

    <form method="POST" action="{{ route('admin.x.'.$action.'.create') }}">
        @csrf

        <div class="p-6">
            <table class="min-w-full text-sm">
                <thead>
                    <tr>
                        <th>選択</th>
                        <th>ID</th>
                        <th>名前</th>
                    </tr>
                </thead>
                <tbody>
                @foreach(\App\Models\User::all() as $user)
                    <tr>
                        <td><input type="checkbox" name="user_ids[]" value="{{ $user->id }}"></td>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <button class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded">次へ</button>
        </div>
    </form>
</x-app-layout>
EOF

cat << 'EOF' > $VIEW/admin/x_actions/post/create.blade.php
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            投稿作成
        </h2>
    </x-slot>

    <form method="POST" action="{{ route('admin.x.post.execute') }}">
        @csrf
        @foreach($selectedUsers as $id)
            <input type="hidden" name="user_ids[]" value="{{ $id }}">
        @endforeach

        <div class="p-6 space-y-4">
            <div>
                <label>見出し</label>
                <input type="text" name="heading" class="w-full border rounded">
            </div>

            <div>
                <label>本文</label>
                <textarea name="body" rows="5" class="w-full border rounded"></textarea>
            </div>

            <div>
                <label>ハッシュタグ</label>
                <input type="text" name="hashtags" class="w-full border rounded">
            </div>

            <button class="px-4 py-2 bg-green-600 text-white rounded">投稿する</button>
        </div>
    </form>
</x-app-layout>
EOF

cat << 'EOF' > $VIEW/admin/x_actions/post/result.blade.php
<x-app-layout>
    <x-slot name="header">
        投稿完了
    </x-slot>

    <div class="p-6">
        <p>投稿が完了しました。</p>
    </div>
</x-app-layout>
EOF

echo "Views created."

echo "=== DONE ==="
