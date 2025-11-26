{{-- resources/views/client/dashboard.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                クライアントダッシュボード
            </h2>

            {{-- 右上のメインボタン --}}
            <a href="{{ route('client.campaigns.create') }}"
               class="px-4 py-2 text-sm font-semibold rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                ＋ 新規キャンペーン作成
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- ✅ クライアント用サブヘッダー（タブ風ナビ） --}}
            <nav class="bg-white shadow-sm sm:rounded-lg">
                <div class="px-6 py-3 flex items-center gap-6 text-sm">
                    {{-- ダッシュボード（アクティブ） --}}
                    <a href="{{ route('client.dashboard') }}"
                       class="pb-2 border-b-2 border-indigo-600 text-indigo-700 font-semibold">
                        ダッシュボード
                    </a>

                    {{-- キャンペーン作成 --}}
                    <a href="{{ route('client.campaigns.create') }}"
                       class="pb-2 border-b-2 border-transparent text-gray-600 hover:text-gray-800 hover:border-gray-300">
                        キャンペーン作成
                    </a>

                    {{-- 将来：キャンペーン一覧を作ったらここに追加
                    <a href="{{ route('client.campaigns.index') }}"
                       class="pb-2 border-b-2 border-transparent text-gray-600 hover:text-gray-800 hover:border-gray-300">
                        キャンペーン一覧
                    </a>
                    --}}
                </div>
            </nav>

            {{-- 挨拶 --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-lg font-semibold">
                        {{ $user->name }} さん、こんにちは！
                    </p>
                    <p class="text-sm text-gray-500 mt-1">
                        あなたのキャンペーン管理ページです。
                    </p>
                </div>
            </div>

            {{-- サマリーカード --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-gray-500 mb-2">キャンペーン数</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalCampaigns }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-gray-500 mb-2">ターゲット総数</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalTargets }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-gray-500 mb-2">承認済みターゲット</h3>
                    <p class="text-3xl font-bold text-emerald-600">{{ $approvedTargets }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-gray-500 mb-2">保留 / 非承認</h3>
                    <p class="text-lg font-semibold text-amber-600">
                        保留: {{ $pendingTargets }}
                    </p>
                    <p class="text-lg font-semibold text-rose-600">
                        非承認: {{ $rejectedTargets }}
                    </p>
                </div>
            </div>

            {{-- アクションカード --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-800">新しいキャンペーンを作成</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            新商品やサービスのPR投稿依頼を作成します。
                        </p>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('client.campaigns.create') }}"
                           class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                            ＋ 新規キャンペーン作成
                        </a>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-800">最近のキャンペーンを確認</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            直近で作成したキャンペーンの状況を確認できます。
                        </p>
                    </div>
                    <div class="mt-4">
                        <a href="#recent-campaigns"
                           class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 border">
                            ↓ 下の「最近のキャンペーン」へ移動
                        </a>
                    </div>
                </div>
            </div>

            {{-- 最近のキャンペーン一覧 --}}
            <div id="recent-campaigns" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            最近のキャンペーン
                        </h3>
                    </div>

                    @if ($recentCampaigns->isEmpty())
                        <p class="text-sm text-gray-500">
                            まだキャンペーンが登録されていません。右上の「＋ 新規キャンペーン作成」から作成できます。
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                <tr class="border-b text-gray-500">
                                    <th class="px-3 py-2 text-left">ID</th>
                                    <th class="px-3 py-2 text-left">タイトル</th>
                                    <th class="px-3 py-2 text-left">作成日</th>
                                    <th class="px-3 py-2 text-left">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($recentCampaigns as $campaign)
                                    <tr class="border-b last:border-0">
                                        <td class="px-3 py-2">{{ $campaign->id }}</td>
                                        <td class="px-3 py-2">
                                            {{ $campaign->title ?? '（タイトル未設定）' }}
                                        </td>
                                        <td class="px-3 py-2">
                                            {{ optional($campaign->created_at)->format('Y-m-d') }}
                                        </td>
                                        <td class="px-3 py-2">
                                            <a href="{{ route('client.campaigns.show', $campaign) }}"
                                               class="text-indigo-600 hover:underline">
                                                詳細を見る
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>



