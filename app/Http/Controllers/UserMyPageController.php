<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserMetric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMyPageController extends Controller
{
    public function show()
    {
        /** @var User $user */
        $user = Auth::user();

        // user_profiles テーブル
        $profile = $user->profile;

        // user_metrics テーブル（なければ新規オブジェクト）
        $metrics = UserMetric::firstOrNew([
            'user_id' => $user->id,
        ]);

        // ※ 将来的にここで X API から最新数字を取得して $metrics を更新する想定

        return view('user.mypage', [
            'user'    => $user,
            'profile' => $profile,
            'metrics' => $metrics,
        ]);
    }

    public function updateBankInfo(Request $request)
    {
        $user    = Auth::user();
        $profile = $user->profile;

        $data = $request->validate([
            'bank_name'           => ['nullable', 'string', 'max:255'],
            'bank_branch'         => ['nullable', 'string', 'max:255'],
            'bank_account_type'   => ['nullable', 'string', 'max:50'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'bank_account_holder' => ['nullable', 'string', 'max:255'],
        ]);

        $profile->fill($data);
        $profile->save();

        return redirect()
            ->route('user.mypage')
            ->with('status', '銀行情報を更新しました。');
    }
}
