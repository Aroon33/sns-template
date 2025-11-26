<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('profile'); // profile リレーションも一緒に読む

        return view('user.dashboard', [
            'user'    => $user,
            'profile' => $user->profile, // Bladeで使いやすく別名で渡す
        ]);
    }
}


