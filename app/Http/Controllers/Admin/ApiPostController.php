<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendApiPostJob;
use App\Models\ApiPost;
use App\Models\ApiPostTarget;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiPostController extends Controller
{
    /**
     * API投稿一覧 /admin/api-posts
     */
    public function index()
    {
        $posts = ApiPost::with('targets')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.api_posts.index', compact('posts'));
    }

    /**
     * 作成フォーム /admin/api-posts/create
     */
    public function create()
    {
        // 対象ユーザー（例：role = general）
        $users = User::with('profile')
            ->where('role', 'general')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.api_posts.create', compact('users'));
    }

    /**
     * 保存処理 POST /admin/api-posts
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'body'         => ['required', 'string'],
            'hashtags'     => ['nullable', 'string', 'max:255'],
            'image'        => ['nullable', 'image', 'max:5120'],
            'user_ids'     => ['required', 'array', 'min:1'],
            'user_ids.*'   => ['integer', 'exists:users,id'],
        ], [
            'user_ids.required' => '少なくとも1人のユーザーを選択してください。',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('api_posts', 'public');
        }

        // api_posts 作成
        $apiPost = ApiPost::create([
            'created_by' => Auth::id(),
            'title'      => $data['title'],
            'body'       => $data['body'],
            'hashtags'   => $data['hashtags'] ?? '',
            'image_path' => $imagePath,
        ]);

        // 対象ユーザー分のターゲット作成
        foreach ($data['user_ids'] as $userId) {
            ApiPostTarget::create([
                'api_post_id' => $apiPost->id,
                'user_id'     => $userId,
                'status'      => 'pending',
            ]);
        }

        // 送信ジョブ投入（queue未設定ならコメントアウトしてもOK）
        SendApiPostJob::dispatch($apiPost);

        return redirect()
            ->route('admin.api_posts.index')
            ->with('status', 'API投稿リクエストを登録しました。');
    }

    /**
     * 詳細 /admin/api-posts/{apiPost}
     */
    public function show(ApiPost $apiPost)
    {
        $apiPost->load(['creator', 'targets.user.profile']);

        return view('admin.api_posts.show', [
            'post' => $apiPost,
        ]);
    }
}

