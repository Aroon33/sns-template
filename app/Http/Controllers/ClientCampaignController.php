<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Campaign;

class ClientCampaignController extends Controller
{
    /**
     * 案件作成フォーム表示
     */
    public function create()
    {
        $user = Auth::user();

        return view('client.campaigns.create', compact('user'));
    }

    /**
     * 案件登録
     */
    public function store(Request $request)
    {
        // 入力チェック
        $validated = $request->validate([
            'title'              => ['required', 'string', 'max:255'],
            'lp_url'             => ['nullable', 'url', 'max:255'],
            'daily_budget_max'   => ['nullable', 'numeric', 'min:0'],
            'desired_post_count' => ['nullable', 'integer', 'min:0'],
        ]);

        // ここを Campaign::create() ではなく、1つずつ代入に変更
        $campaign = new Campaign();
        $campaign->title              = $validated['title'];
        $campaign->lp_url             = $validated['lp_url'] ?? null;
        $campaign->daily_budget_max   = $validated['daily_budget_max'] ?? null;
        $campaign->desired_post_count = $validated['desired_post_count'] ?? null;

        // 集計系は初期値 0
        $campaign->posts_count    = 0;
        $campaign->posters_count  = 0;
        $campaign->likes_count    = 0;
        $campaign->retweets_count = 0;
        $campaign->views_count    = 0;
        $campaign->total_ad_cost  = 0;
        $campaign->today_ad_cost  = 0;

        // ★ NOT NULL な post_text をとりあえず空文字で埋める
        $campaign->post_text = '';

        $campaign->save();


        return redirect()
            ->route('client.campaigns.show', $campaign->id)
            ->with('status', '案件を作成しました。');
    }

    /**
     * 案件詳細（数字確認ページ）
     */
    public function show(Campaign $campaign)
    {
        $user = Auth::user();

        return view('client.campaigns.show', compact('user', 'campaign'));
    }
}


