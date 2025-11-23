<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ジャンルマスタのシーディング
        $this->call(GenreSeeder::class);

        // 必要であれば、今後ここに他のSeederも追加していきます。
        // 例:
        // $this->call(UserSeeder::class);
    }
}

