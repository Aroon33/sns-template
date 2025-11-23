<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $user = $request->user();

        // 全ジャンル取得
        $genres = Genre::orderBy('sort_order')->orderBy('id')->get();

        // プロフィール（存在しなければ空インスタンスを作る）
        $profile = $user->profile ?: $user->profile()->make();

        // 選択中のジャンルのID（多対多）
        $selectedGenreIds = $user->genres()->pluck('genres.id')->toArray();

        return view('profile.edit', [
            'user' => $user,
            'profile' => $profile,
            'genres' => $genres,
            'selectedGenreIds' => $selectedGenreIds,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();

        // 名前とメール更新（Breeze標準）
        $user->fill($request->validated());
        $user->save();

        // プロフィール本体の保存
        $profileData = [
            'location' => $request->input('location'),
            'bio' => $request->input('bio'),
        ];

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        // 興味ジャンルの同期
        $genreIds = $request->input('genre_ids', []);
        $user->genres()->sync($genreIds);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
