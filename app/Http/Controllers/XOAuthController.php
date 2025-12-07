<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class XOAuthController extends Controller
{
    // とりあえず動作確認用：ここからXの認証に飛ばす処理をあとで書く
    public function redirect(Request $request)
    {
        return response('X OAuth redirect 動いてます', 200);
    }

    // とりあえず動作確認用：コールバック確認
    public function callback(Request $request)
    {
        return response('X OAuth callback 動いてます', 200);
    }
}
