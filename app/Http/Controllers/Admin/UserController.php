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
        $users = User::with('profile')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
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

