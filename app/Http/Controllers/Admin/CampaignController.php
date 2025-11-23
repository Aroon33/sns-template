<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Genre;
use App\Models\User;
use App\Models\CampaignTarget;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * 案件一覧
     */
    public function index()
    {
        $campaigns = Campaign::with('genres')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.campaigns.index', compact('campaigns'));
    }

    /**
     * 新規案件作成フォーム
     */
    public function create()
    {
        $genres = Genre::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.campaigns.create', compact('genres'));
    }

    /**
     * 案件の保存
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'post_text'     => ['required', 'string'],
            'min_followers' => ['nullable', 'integer', 'min:0'],
            'max_followers' => ['nullable', 'integer', 'min:0'],
            'start_at'      => ['nullable', 'date'],
            'end_at'        => ['nullable', 'date', 'after_or_equal:start_at'],
            'status'        => ['required', 'string', 'in:draft,active,closed'],
            'genre_ids'     => ['nullable', 'array'],
            'genre_ids.*'   => ['integer', 'exists:genres,id'],
        ]);

        $campaign = Campaign::create($validated);

        $genreIds = $request->input('genre_ids', []);
        $campaign->genres()->sync($genreIds);

        return redirect()
            ->route('admin.campaigns.index')
            ->with('status', 'campaign-created');
    }

    /**
     * 編集フォーム
     */
    public function edit(Campaign $campaign)
    {
        $genres = Genre::orderBy('sort_order')->orderBy('id')->get();
        $selectedGenreIds = $campaign->genres()->pluck('genres.id')->toArray();

        return view('admin.campaigns.edit', compact('campaign', 'genres', 'selectedGenreIds'));
    }

    /**
     * 更新処理
     */
    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'post_text'     => ['required', 'string'],
            'min_followers' => ['nullable', 'integer', 'min:0'],
            'max_followers' => ['nullable', 'integer', 'min:0'],
            'start_at'      => ['nullable', 'date'],
            'end_at'        => ['nullable', 'date', 'after_or_equal:start_at'],
            'status'        => ['required', 'string', 'in:draft,active,closed'],
            'genre_ids'     => ['nullable', 'array'],
            'genre_ids.*'   => ['integer', 'exists:genres,id'],
        ]);

        $campaign->update($validated);

        $genreIds = $request->input('genre_ids', []);
        $campaign->genres()->sync($genreIds);

        return redirect()
            ->route('admin.campaigns.index')
            ->with('status', 'campaign-updated');
    }

    /**
     * 削除
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return redirect()
            ->route('admin.campaigns.index')
            ->with('status', 'campaign-deleted');
    }

    /**
     * ★ 案件ごとの対象ユーザー一覧（候補＋追加済み判定付き）
     */
    public function targets(Campaign $campaign)
    {
        // この案件に紐づくジャンルID一覧
        $genreIds = $campaign->genres()->pluck('genres.id')->toArray();

        // ユーザーをプロフィール＆ジャンル付きでロード
        $query = User::with(['profile', 'genres']);

        // フォロワー条件
        $min = $campaign->min_followers;
        $max = $campaign->max_followers;

        if (!is_null($min) || !is_null($max)) {
            $query->whereHas('profile', function ($q) use ($min, $max) {
                if (!is_null($min)) {
                    $q->where('followers_count', '>=', $min);
                }
                if (!is_null($max)) {
                    $q->where('followers_count', '<=', $max);
                }
            });
        }

        // ジャンル条件（案件にジャンルが紐づいている場合のみ）
        if (!empty($genreIds)) {
            $query->whereHas('genres', function ($q) use ($genreIds) {
                $q->whereIn('genres.id', $genreIds);
            });
        }

        $users = $query->get();

        // ★ すでにこの案件のターゲットになっている user_id 一覧
        $addedUserIds = $campaign->targets()->pluck('user_id')->toArray();

        return view('admin.campaigns.targets', compact(
            'campaign',
            'users',
            'genreIds',
            'min',
            'max',
            'addedUserIds'
        ));
    }

    /**
     * ★ 単体追加（今は「一括追加」メインなので使わなくてもOK）
     */
    public function addTarget(Request $request, Campaign $campaign)
    {
        $userId = $request->input('user_id');

        CampaignTarget::updateOrCreate(
            [
                'campaign_id' => $campaign->id,
                'user_id'     => $userId,
            ],
            [
                'status'      => 'approved',
            ]
        );

        return back()->with('status', 'target-added');
    }

    /**
     * ★ 一括追加（複数ユーザーをまとめてターゲット登録）
     */
    public function bulkAdd(Request $request, Campaign $campaign)
    {
        $userIds = $request->input('user_ids', []);

        if (empty($userIds)) {
            return back()->with('status', 'no-users-selected');
        }

        foreach ($userIds as $userId) {
            CampaignTarget::updateOrCreate(
                [
                    'campaign_id' => $campaign->id,
                    'user_id'     => $userId,
                ],
                [
                    'status' => 'approved',
                ]
            );
        }

        return back()->with('status', 'bulk-added');
    }
}



