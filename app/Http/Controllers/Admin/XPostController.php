<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CampaignPost;
use App\Models\CampaignPostRun;
use App\Services\X\TweetService;

class XPostController extends Controller
{
    /**
     * ユーザー選択ページ
     */
    public function index()
    {
        // ユーザー＋プロフィールを取得（共通UI）
        $users = User::with('profile')
            ->orderByDesc('id')
            ->paginate(30);

        return view('admin.x_actions.common.user_select', [
            'actionName'  => 'post',
            'submitRoute' => route('admin.x.post.create'),
            'users'       => $users,
        ]);
    }


    /**
     * 投稿作成ページを表示
     */
    public function create(Request $request)
    {
        $selectedUsers = $request->user_ids;

        if (!$selectedUsers || count($selectedUsers) === 0) {
            return redirect()
                ->back()
                ->with('error', 'ユーザーが選択されていません。');
        }

        return view('admin.x_actions.post.create', [
            'selectedUsers' => $selectedUsers
        ]);
    }


    /**
     * 投稿実行処理
     */
    public function execute(Request $request, TweetService $tweetService)
    {
        $request->validate([
            'user_ids' => 'required|array|min:1',
            'body'     => 'required|string|max:140',
            'heading'  => 'nullable|string|max:255',
            'hashtags' => 'nullable|string|max:255',
        ]);

        $selectedUsers = $request->user_ids;
        $heading       = $request->heading;
        $body          = $request->body;
        $hashtags      = $request->hashtags;
        $images        = $request->file('images'); // 今後 media API に利用

        $postText = trim($heading . "\n\n" . $body . "\n\n" . $hashtags);

        // ★ 投稿テンプレ（CampaignPost）として保存する場合（任意）
        // 今は簡易保存として campaign_posts に入れます
        $campaignPost = CampaignPost::create([
            'campaign_id' => 0, // 必要に応じて紐付ける
            'heading'     => $heading,
            'body'        => $body,
            'hashtags'    => $hashtags,
            'image_path'  => null,
        ]);

        // ★ 各ユーザーへ投稿実行
        foreach ($selectedUsers as $userId) {

            // Tweet API で投稿
            $result = $tweetService->tweet($postText);

            // tweet_id が返ってくる（成功時）
            $tweetId = $result['data']['id'] ?? null;

            // 投稿実行ログ保存
            CampaignPostRun::create([
                'campaign_post_id' => $campaignPost->id,
                'user_id'          => $userId,
                'tweet_id'         => $tweetId,
                'status'           => $tweetId ? 'sent' : 'failed',
                'response_json'    => $result,
            ]);
        }

        return view('admin.x_actions.post.result');
    }
}
