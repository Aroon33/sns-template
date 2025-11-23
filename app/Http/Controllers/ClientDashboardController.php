<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Campaign;
use App\Models\CampaignTarget;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 全体のざっくり集計（まずはここから）
        $totalCampaigns      = Campaign::count();
        $totalTargets        = CampaignTarget::count();
        $approvedTargets     = CampaignTarget::where('status', 'approved')->count();
        $pendingTargets      = CampaignTarget::where('status', 'pending')->count();
        $rejectedTargets     = CampaignTarget::where('status', 'rejected')->count();

        // 最新キャンペーン一覧（10件だけ表示）
        $recentCampaigns = Campaign::latest()->take(10)->get();

        return view('client.dashboard', [
            'user'             => $user,
            'totalCampaigns'   => $totalCampaigns,
            'totalTargets'     => $totalTargets,
            'approvedTargets'  => $approvedTargets,
            'pendingTargets'   => $pendingTargets,
            'rejectedTargets'  => $rejectedTargets,
            'recentCampaigns'  => $recentCampaigns,
        ]);
    }
}

