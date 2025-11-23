<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class XAuthController extends Controller
{
    /**
     * X（旧Twitter）へリダイレクト
     */
    public function redirect()
    {
        // セッションを使わない構成（APIゲートウェイ等）の場合は stateless() を付ける
        // return Socialite::driver('twitter-oauth-2')->stateless()->redirect();

        return Socialite::driver('twitter-oauth-2')->redirect();
    }

    /**
     * X（旧Twitter）からコールバック
     */
    public function callback()
    {
        try {
            // X API からユーザー情報を取得
            $xUser = Socialite::driver('twitter-oauth-2')->user();
            // stateless() を使う場合はこちら：
            // $xUser = Socialite::driver('twitter-oauth-2')->stateless()->user();
        } catch (\Exception $e) {
            // ここでログに残しておくと後で追跡しやすい
            // \Log::error('X login error', ['message' => $e->getMessage()]);

            return redirect('/login')
                ->withErrors('Xログインに失敗しました。時間をおいて再度お試しください。');
        }

        // X から取得できるデータ
        $xId      = $xUser->getId();           // X内部ID（必ずある想定）
        $name     = $xUser->getName() ?: $xUser->getNickname() ?: 'Xユーザー';
        $username = $xUser->getNickname() ?: ('x_' . $xId);
        $avatar   = $xUser->getAvatar();       // プロフィール画像URL（null の可能性あり）
        $email    = $xUser->getEmail();        // ほとんどの場合 null

        // メールが返ってこない場合は X ID ベースでダミーを作成
        if (empty($email)) {
            $email = "x_{$xId}@example.com";
        }

        // users テーブル
        // - 今回は email をキーにしてユーザーを特定
        // - すでに同じメールのユーザーがいれば更新、いなければ作成
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name'     => $name,
                // 実際には使わないダミーパスワード（ログインは常に X 経由）
                'password' => bcrypt('x_oauth_dummy_password'),
                'role'     => 'general',
            ]
        );

        // user_profiles リレーション経由でプロフィールを保存
        // ※ User モデルに profile() リレーション（hasOne）が定義されている前提です
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'display_name' => $name,
                'x_username'   => $username,
                'x_user_id'    => $xId,
                'avatar_url'   => $avatar,
            ]
        );

        // ログインさせる
        Auth::login($user, true); // 「remember me」したい場合は true

        // 好きなページに飛ばす（ダッシュボード等）
        return redirect('/dashboard');
    }
}


