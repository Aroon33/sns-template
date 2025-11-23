<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            クライアント案件詳細
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 基本情報 --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-2">
                <h3 class="text-lg font-semibold text-gray-800">
                    {{ $campaign->title ?? '案件名未設定' }}
                </h3>
                <p class="text-sm text-gray-500">
                    作成日：{{ optional($campaign->created_at)->format('Y-m-d H:i') }}
                </p>
                @if ($campaign->lp_url)
                    <p class="text-sm">
                        LP：<a href="{{ $campaign->lp_url }}" target="_blank" class="text-blue-600 hover:underline">
                            {{ $campaign->lp_url }}
                        </a>
                    </p>
                @endif
            </div>

            {{-- 数字サマリー --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">投稿数</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $campaign->posts_count }}</p>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">投稿人数</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $campaign->posters_count }}</p>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">いいね数</p>
                    <p class="text-2xl font-bold text-rose-600">{{ number_format($campaign->likes_count) }}</p>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">リツイート数</p>
                    <p class="text-2xl font-bold text-sky-600">{{ number_format($campaign->retweets_count) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">閲覧数</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($campaign->views_count) }}</p>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">合計広告金額</p>
                    <p class="text-2xl font-bold text-emerald-600">
                        {{ number_format($campaign->total_ad_cost, 2) }} 円
                    </p>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">当日の広告金額</p>
                    <p class="text-2xl font-bold text-amber-600">
                        {{ number_format($campaign->today_ad_cost, 2) }} 円
                    </p>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-4 text-sm text-gray-600">
                <p>※ 投稿数・いいね数などの数字は、将来的に X API から取得したデータを元に更新する想定です。</p>
            </div>
        </div>
    </div>
</x-app-layout>


