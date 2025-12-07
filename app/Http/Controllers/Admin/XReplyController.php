<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\X\ReplyService;

class XReplyController extends Controller
{
    public function index()
    {
        return view('admin.x_actions.common.user_select', [
            'action' => 'reply'
        ]);
    }

    public function create(Request $request)
    {
        return view('admin.x_actions.reply.create', [
            'selectedUsers' => $request->user_ids,
            'tweet_id' => $request->tweet_id
        ]);
    }

    public function execute(Request $request, ReplyService $replyService)
    {
        foreach ($request->user_ids ?? [] as $userId) {
            $replyService->reply($userId, $request->tweet_id, $request->body);
        }
        return "Done";
    }
}
