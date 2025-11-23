<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Genre;

class XApiUsersSeeder extends Seeder
{
    public function run(): void
    {
        // storage/app/x_users.json を読み込む
        $path = storage_path('app/x_users.json');

        if (! file_exists($path)) {
            $this->command?->error("x_users.json が見つかりません: {$path}");
            return;
        }

        $json = file_get_contents($path);
        $data = json_decode($json, true);

        if (! isset($data['data']) || ! is_array($data['data'])) {
            $this->command?->error("x_users.json の形式が想定と違います（data 配列がありません）");
            return;
        }

        foreach ($data['data'] as $item) {
            // X API の構造を想定
            $xId       = $item['id'] ?? null;
            $name      = $item['name'] ?? null;       // 表示名（漢字）
            $username  = $item['username'] ?? null;   // @ID
            $location  = $item['location'] ?? null;
            $bio       = $item['description'] ?? null;
            $followers = $item['public_metrics']['followers_count'] ?? 0;

            if (! $xId || ! $username) {
                continue;
            }

            // users テーブルにユーザーを作成 or 更新
            // ※メールはダミー（xid@example.com）にしています
            $user = User::updateOrCreate(
                ['email' => $username . '@example.com'],
                [
                    'name'     => $username,                  // 内部的な name は @ID でもOK
                    'password' => Hash::make('password'),     // ダミー
                    'role'     => 'general',
                ]
            );

            // user_profiles を更新
            $profile = $user->profile()->firstOrCreate(
                ['user_id' => $user->id],
                [
                    'followers_count' => $followers,
                    'location'        => $location,
                    'bio'             => $bio,
                ]
            );

            $profile->display_name = $name;       // 漢字名
            $profile->x_username   = $username;   // @ID
            $profile->x_user_id    = $xId;        // X内部ID
            $profile->followers_count = $followers;
            $profile->location        = $location;
            $profile->bio             = $bio;
            $profile->save();

            // 興味ジャンルの自動付与は、必要ならここにロジックを足せます
            // 例: プロフィール文に "FX" が含まれていれば "FX・為替" を紐付ける… 等
        }
    }
}

