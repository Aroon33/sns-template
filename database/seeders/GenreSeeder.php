<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            '不動産投資',
            '不動産売買',
            '副業',
            '物販・転売',
            '株式投資',
            '仮想通貨',
            'FX・為替',
            '保険',
            '節税・税金',
            '起業・独立',
            'フリーランス',
            '住宅・リフォーム',
            '教育・資格',
            '転職・キャリア',
            '恋愛・婚活',
            '美容・コスメ',
            '健康・フィットネス',
            '医療・クリニック',
            'グルメ・飲食',
            '旅行・観光',
            '子育て・ファミリー',
            'Webサービス・アプリ',
            'SaaS・クラウド',
            'ガジェット・家電',
            'ゲーム・エンタメ',
            'NFT・ブロックチェーン',
        ];

        $sort = 1;

        foreach ($names as $name) {
            Genre::updateOrCreate(
                ['name' => $name],
                ['sort_order' => $sort++]
            );
        }
    }
}

