<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\X\LikeService;

class XLikeController extends Controller
{
    public function index()
    {
        return view('admin.x_actions.common.user_select', [
            'action' => 'like'
        ]);
    }

    public function execute(Request $request, LikeService $likeService)
    {
        foreach ($request->user_ids ?? [] as $userId) {
            $likeService->like($userId, $request->tweet_id);
        }
        return "Done";
    }
}
