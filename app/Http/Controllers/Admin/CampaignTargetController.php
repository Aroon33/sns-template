<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignTarget;
use Illuminate\Http\Request;

class CampaignTargetController extends Controller
{
    /**
     * ★ 確定ターゲット一覧
     */
    public function index(Campaign $campaign)
    {
        $targets = CampaignTarget::with(['user.profile', 'user.genres'])
            ->where('campaign_id', $campaign->id)
            ->orderBy('id')
            ->get();

        return view('admin.campaign_targets.index', compact('campaign', 'targets'));
    }

    /**
     * ★ ステータス変更（approved / pending / rejected）
     */
    public function update(Request $request, Campaign $campaign, CampaignTarget $target)
    {
        if ($target->campaign_id !== $campaign->id) {
            abort(404); // 不正アクセス対策
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:approved,pending,rejected'],
        ]);

        $target->update($validated);

        return back()->with('status', 'target-updated');
    }
    /**
     * ★ 全ターゲット一覧（キャンペーン指名なし）
     */
    public function all()
    {
        $targets = CampaignTarget::with(['campaign', 'user.profile', 'user.genres'])
            ->orderBy('id')
            ->get();

        // campaign は特定しないので targets だけ渡す
        return view('admin.campaign_targets.all', compact('targets'));
    }

    /**
     * ★ ターゲットから外す（削除）
     */
    public function destroy(Campaign $campaign, CampaignTarget $target)
    {
        if ($target->campaign_id !== $campaign->id) {
            abort(404);
        }

        $target->delete();

        return back()->with('status', 'target-deleted');
    }
}

