{{-- resources/views/client/campaigns/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                キャンペーン詳細
            </h2>

            <a href="{{ route('client.dashboard') }}"
               class="px-4 py-2 text-sm rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 border">
                ← ダッシュボードに戻る
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 基本情報 --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-3">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ $campaign->title ?? '案件名未設定' }}
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            作成日：{{ optional($campaign->created_at)->format('Y-m-d H:i') }}
                        </p>
                    </div>

                    {{-- 簡易ステータス（必要に応じて拡張用） --}}
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                            キャンペーンID：{{ $campaign->id }}
                        </span>
                    </div>
                </div>

                @if ($campaign->lp_url)
                    <div class="text-sm">
                        <span class="font-medium text-gray-700">LP：</span>
                        <a href="{{ $campaign->lp_url }}"
                           target="_blank"
                           class="text-blue-600 hover:underline break-all">
                            {{ $campaign->lp_url }}
                        </a>
                    </div>
                @endif
            </div>

            {{-- 設定サマリー（予算・人数など） --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-4 space-y-1">
                    <p class="text-xs text-gray-500">1日の最大広告費</p>
                    <p class="text-2xl font-bold text-emerald-600">
                        @if (!is_null($campaign->daily_budget_max))
                            {{ number_format($campaign->daily_budget_max, 0) }} 円
                        @else
                            -
                        @endif
                    </p>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-4 space-y-1">
                    <p class="text-xs text-gray-500">希望投稿人数</p>
                    <p class="text-2xl font-bold text-gray-800">
                        @if (!is_null($campaign->desired_post_count))
                            {{ number_format($campaign->desired_post_count) }} 人
                        @else
                            -
                        @endif
                    </p>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-4 space-y-1">
                    <p class="text-xs text-gray-500">キャンペーンID</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ $campaign->id }}
                    </p>
                </div>
            </div>

            {{-- 実績サマリー（投稿・いいね・RTなど） --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">投稿数</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ number_format($campaign->posts_count ?? 0) }}
                    </p>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">投稿人数</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ number_format($campaign->posters_count ?? 0) }}
                    </p>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">いいね数</p>
                    <p class="text-2xl font-bold text-rose-600">
                        {{ number_format($campaign->likes_count ?? 0) }}
                    </p>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">リツイート数</p>
                    <p class="text-2xl font-bold text-sky-600">
                        {{ number_format($campaign->retweets_count ?? 0) }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">閲覧数</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ number_format($campaign->views_count ?? 0) }}
                    </p>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">合計広告金額</p>
                    <p class="text-2xl font-bold text-emerald-600">
                        @if (!is_null($campaign->total_ad_cost))
                            {{ number_format($campaign->total_ad_cost, 2) }} 円
                        @else
                            0.00 円
                        @endif
                    </p>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">当日の広告金額</p>
                    <p class="text-2xl font-bold text-amber-600">
                        @if (!is_null($campaign->today_ad_cost))
                            {{ number_format($campaign->today_ad_cost, 2) }} 円
                        @else
                            0.00 円
                        @endif
                    </p>
                </div>
            </div>

            {{-- 補足メモ --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-4 text-sm text-gray-600">
                <p>※ 投稿数・いいね数などの数字は、将来的に X API から取得したデータを元に自動更新する想定です。</p>
                <p class="mt-1">※ 現時点では、テストデータまたは手動入力の値が表示されています。</p>
            </div>

        </div>
    </div>
</x-app-layout>
