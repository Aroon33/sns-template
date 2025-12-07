<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\X\RetweetService;

class XRetweetController extends Controller
{
    public function index()
    {
        return view('admin.x_actions.common.user_select', [
            'action' => 'retweet'
        ]);
    }

    public function execute(Request $request, RetweetService $rtService)
    {
        foreach ($request->user_ids ?? [] as $userId) {
            $rtService->retweet($userId, $request->tweet_id);
        }
        return "Done";
    }
}
