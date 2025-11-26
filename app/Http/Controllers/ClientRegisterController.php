<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ClientProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientRegisterController extends Controller
{
    /**
     * クライアント会員登録フォーム表示
     */
    public function create()
    {
        return view('client.auth.register');
    }

    /**
     * クライアント会員登録処理
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            // User 情報
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            // クライアント Profile 情報
            'name'         => ['required', 'string', 'max:255'],
            'type'         => ['required', 'in:individual,corp'],
            'contact_email'=> ['nullable', 'email', 'max:255'],
            'contact_tel'  => ['nullable', 'string', 'max:50'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'department'   => ['nullable', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
        ]);

        // User 作成（role を client に固定）
        $user = User::create([
            'name'     => $data['name'],  // 表示名として name を利用
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'client',
        ]);

        // クライアントプロフィール作成
        ClientProfile::create([
            'user_id'       => $user->id,
            'name'          => $data['name'],
            'type'          => $data['type'],
            'contact_email' => $data['contact_email'] ?? $data['email'],
            'contact_tel'   => $data['contact_tel'] ?? null,
            'company_name'  => $data['company_name'] ?? null,
            'department'    => $data['department'] ?? null,
            'description'   => $data['description'] ?? null,
        ]);

        // 自動ログイン
        Auth::login($user);

        // クライアントダッシュボードへ
        return redirect()->route('client.dashboard');
    }
}

