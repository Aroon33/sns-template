<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiPost;
use App\Models\ApiPostTarget;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApiPostController extends Controller
{
    /**
     * 投稿作成フォーム表示
     */
    public function create()
    {
        // 一般ユーザーだけを対象にする例（必要に応じて変更）
        $users = User::with('profile')
            ->where('role', 'general')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.api_posts.create', compact('users'));
    }

    /**
     * 投稿内容＋対象ユーザーを保存
     * （実際のAPI投稿は今後ジョブ等で実装想定）
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'body'         => ['required', 'string'],
            'hashtags'     => ['nullable', 'string', 'max:255'],
            'image'        => ['nullable', 'image', 'max:5120'], // 5MB
            'user_ids'     => ['required', 'array', 'min:1'],
            'user_ids.*'   => ['integer', 'exists:users,id'],
        ], [
            'user_ids.required' => '少なくとも1人のユーザーを選択してください。',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            // storage/app/public/api_posts/... に保存
            $imagePath = $request->file('image')->store('api_posts', 'public');
        }

        // api_posts レコード作成
        $apiPost = ApiPost::create([
            'created_by' => Auth::id(),
            'title'      => $data['title'],
            'body'       => $data['body'],
            'hashtags'   => $data['hashtags'] ?? '',
            'image_path' => $imagePath,
        ]);

        // 対象ユーザーごとのターゲットを作成
        foreach ($data['user_ids'] as $userId) {
            ApiPostTarget::create([
                'api_post_id' => $apiPost->id,
                'user_id'     => $userId,
                'status'      => 'pending',
            ]);
        }

        // ★ 実際のX API呼び出しはここでジョブに投げる想定
        // dispatch(new SendApiPostJob($apiPost));

        return redirect()
            ->route('admin.dashboard')
            ->with('status', 'API投稿リクエストを登録しました。（実際の送信処理は今後実装）');
    }
}
