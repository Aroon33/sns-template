<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Campaign;
use App\Models\CampaignTarget;

class DashboardController extends Controller
{
    public function index()
    {
        // 件数集計
        $usersCount     = User::count();
        $campaignsCount = Campaign::count();
        $targetsCount   = CampaignTarget::count();

        return view('admin.dashboard', [
            'usersCount'     => $usersCount,
            'campaignsCount' => $campaignsCount,
            'targetsCount'   => $targetsCount,
        ]);
    }
}



