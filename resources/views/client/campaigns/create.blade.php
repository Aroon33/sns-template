{{-- resources/views/client/campaigns/create.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                新規キャンペーン作成
            </h2>

            <a href="{{ route('client.dashboard') }}"
               class="px-4 py-2 text-sm rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 border">
                ← ダッシュボードに戻る
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- カード --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-8 space-y-8">

                {{-- エラー --}}
                @if ($errors->any())
                    <div class="p-4 bg-red-50 border border-red-300 rounded-md">
                        <p class="font-semibold text-red-700 mb-2">入力内容に問題があります：</p>
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- 完了メッセージ --}}
                @if (session('status'))
                    <div class="p-3 bg-emerald-50 text-emerald-700 border border-emerald-300 rounded-md text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('client.campaigns.store') }}" class="space-y-8">
                    @csrf

                    {{-- 作成日 --}}
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">作成日</label>
                        <p class="text-gray-500 text-sm">{{ now()->format('Y-m-d') }}</p>
                    </div>

                    {{-- 案件名 --}}
                    <div class="space-y-1">
                        <label for="title" class="block text-sm font-medium text-gray-700">
                            案件名 <span class="text-red-500">*</span>
                        </label>
                        <input id="title" name="title" type="text"
                               value="{{ old('title') }}"
                               placeholder="例：新商品キャンペーン / PR投稿依頼 など"
                               class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <p class="text-xs text-gray-500 mt-1">
                            投稿依頼の名称としてユーザーにも表示されます。
                        </p>
                    </div>

                    {{-- LP URL --}}
                    <div class="space-y-1">
                        <label for="lp_url" class="block text-sm font-medium text-gray-700">
                            LP（ランディングページ）URL
                        </label>
                        <input id="lp_url" name="lp_url" type="url"
                               value="{{ old('lp_url') }}"
                               placeholder="https://example.com/product"
                               class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <p class="text-xs text-gray-500 mt-1">
                            任意。LPがある場合に入力してください。
                        </p>
                    </div>

                    {{-- 2カラム --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        {{-- 最大広告費 --}}
                        <div class="space-y-1">
                            <label for="daily_budget_max" class="block text-sm font-medium text-gray-700">
                                1日の最大広告費
                            </label>
                            <div class="flex items-center gap-2">
                                <input id="daily_budget_max" name="daily_budget_max" type="number" step="0.01" min="0"
                                       value="{{ old('daily_budget_max') }}"
                                       class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <span class="text-sm text-gray-500">円</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                1日にかけられる広告費の上限です。
                            </p>
                        </div>

                        {{-- 希望投稿人数 --}}
                        <div class="space-y-1">
                            <label for="desired_post_count" class="block text-sm font-medium text-gray-700">
                                希望投稿人数
                            </label>
                            <input id="desired_post_count" name="desired_post_count" type="number" min="0"
                                   value="{{ old('desired_post_count') }}"
                                   class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <p class="text-xs text-gray-500 mt-1">
                                どれくらいの人数に投稿してほしいかを設定します。
                            </p>
                        </div>
                    </div>

                    {{-- ボタン --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('client.dashboard') }}"
                           class="px-4 py-2 text-sm text-gray-600 hover:underline">
                            キャンセル
                        </a>
                        <button type="submit"
                                class="px-6 py-2 text-sm font-semibold rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                            キャンペーンを作成する
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>


