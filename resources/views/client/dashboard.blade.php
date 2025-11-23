{{-- resources/views/client/dashboard.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            クライアントダッシュボード
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 挨拶 --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ $user->name }} さん、ようこそ！
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

            {{-- 最新キャンペーン一覧 --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            最近のキャンペーン
                        </h3>
                        <a href="{{ route('admin.campaigns.index') }}"
                           class="text-sm text-blue-600 hover:underline">
                            すべてのキャンペーンを見る
                        </a>
                    </div>

                    @if ($recentCampaigns->isEmpty())
                        <p class="text-sm text-gray-500">
                            まだキャンペーンが登録されていません。
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
                                        <td class="px-3 py-2">
                                            {{ $campaign->id }}
                                        </td>
                                        <td class="px-3 py-2">
                                            {{-- title / name など、あるカラムに合わせて表示されます --}}
                                            {{ $campaign->title ?? $campaign->name ?? '（タイトル未設定）' }}
                                        </td>
                                        <td class="px-3 py-2">
                                            {{ optional($campaign->created_at)->format('Y-m-d') }}
                                        </td>
                                        <td class="px-3 py-2">
                                            <a href="{{ route('admin.campaigns.edit', $campaign) }}"
                                               class="text-blue-600 hover:underline">
                                                詳細・編集
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

            {{-- 今後の拡張用メモ --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-sm text-gray-600 space-y-1">
                    <p>・この画面に「応募者一覧」「キャンペーンの進行状況」などを追加できます。</p>
                    <p>・X API から取得した投稿一覧を紐づけて、効果測定グラフを表示することも可能です。</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

