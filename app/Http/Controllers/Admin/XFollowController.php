<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\X\FollowService;

class XFollowController extends Controller
{
    public function index()
    {
        return view('admin.x_actions.common.user_select', [
            'action' => 'follow'
        ]);
    }

    public function execute(Request $request, FollowService $followService)
    {
        foreach ($request->user_ids ?? [] as $userId) {
            $followService->follow($userId, $request->target_id);
        }
        return "Done";
    }
}
