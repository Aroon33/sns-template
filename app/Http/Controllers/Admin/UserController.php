<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMetric;
use App\Models\UserAction;

class UserController extends Controller
{
    public function index()
{
    // ユーザー一覧（profile + metric をロード）
    $users = User::with(['profile', 'metric'])
        ->orderByDesc('id')
        ->paginate(20);

    // 全ユーザー数
    $totalUsers = User::count();

    // 全体のメトリクス合計
    $metricsSummary = UserMetric::selectRaw('
            COALESCE(SUM(followers_count), 0)       AS followers_sum,
            COALESCE(SUM(posts_count), 0)           AS posts_sum,
            COALESCE(SUM(likes_count), 0)           AS likes_sum,
            COALESCE(SUM(retweets_count), 0)        AS rts_sum,
            COALESCE(SUM(impressions_count), 0)     AS impressions_sum
        ')
        ->first();

    return view('admin.users.index', [
        'users'          => $users,
        'totalUsers'     => $totalUsers,
        'metricsSummary' => $metricsSummary,
    ]);
}



    public function show(User $user)
    {
        // プロフィール & メトリクス
        $user->load('profile');

        $metrics = UserMetric::firstOrNew([
            'user_id' => $user->id,
        ]);

        // アクション履歴（新しい順）
        $actions = UserAction::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.users.mypage', [
            'user'    => $user,
            'profile' => $user->profile,
            'metrics' => $metrics,
            'actions' => $actions,
        ]);
    }

    public function storeAction(\Illuminate\Http\Request $request, User $user)
    {
        $data = $request->validate([
            'type' => ['required', 'in:post_request,like_request,rt_request,dm,other'],
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        $data['user_id'] = $user->id;
        $data['status']  = 'pending';

        UserAction::create($data);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('status', 'ユーザーへのアクションを登録しました。');
    }
}

