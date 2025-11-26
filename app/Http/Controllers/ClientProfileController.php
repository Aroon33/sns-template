<?php

namespace App\Http\Controllers;

use App\Models\ClientProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        // client_profiles テーブルに 1:1 で紐付いている想定
        $profile = $user->clientProfile ?? new ClientProfile([
            'type' => 'individual',
        ]);

        return view('client.profile.edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'type'         => ['required', 'in:individual,corp'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'department'   => ['nullable', 'string', 'max:255'],
            'contact_email'=> ['required', 'email', 'max:255'],
            'contact_tel'  => ['nullable', 'string', 'max:50'],
            'description'  => ['nullable', 'string', 'max:2000'],
        ]);

        $profile = $user->clientProfile ?? new ClientProfile(['user_id' => $user->id]);
        $profile->fill($data);
        $profile->save();

        return redirect()
            ->route('client.profile.edit')
            ->with('status', 'クライアントプロフィールを保存しました。');
    }
}
