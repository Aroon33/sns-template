<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            クライアント案件作成
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">

                {{-- バリデーションエラー表示 --}}
                @if ($errors->any())
                    <div class="mb-4 text-sm text-red-600">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('status'))
                    <div class="text-sm text-emerald-600">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('client.campaigns.store') }}" class="space-y-6">
                    @csrf

                    {{-- 作成日（表示だけ） --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            作成日
                        </label>
                        <p class="text-sm text-gray-500">
                            {{ now()->format('Y-m-d') }}
                        </p>
                    </div>

                    {{-- 案件名 --}}
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                            案件名 <span class="text-red-500">*</span>
                        </label>
                        <input id="title" name="title" type="text"
                               value="{{ old('title') }}"
                               class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('title')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- LP URL --}}
                    <div>
                        <label for="lp_url" class="block text-sm font-medium text-gray-700 mb-1">
                            LP（ランディングページ）URL
                        </label>
                        <input id="lp_url" name="lp_url" type="url"
                               value="{{ old('lp_url') }}"
                               placeholder="https://example.com/lp"
                               class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('lp_url')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- 1日の最大広告費 --}}
                        <div>
                            <label for="daily_budget_max" class="block text-sm font-medium text-gray-700 mb-1">
                                広告費（1日の最大金額）
                            </label>
                            <div class="flex items-center gap-2">
                                <input id="daily_budget_max" name="daily_budget_max" type="number" step="0.01" min="0"
                                       value="{{ old('daily_budget_max') }}"
                                       class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <span class="text-sm text-gray-500">円</span>
                            </div>
                            @error('daily_budget_max')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 希望投稿人数 --}}
                        <div>
                            <label for="desired_post_count" class="block text-sm font-medium text-gray-700 mb-1">
                                希望投稿人数
                            </label>
                            <input id="desired_post_count" name="desired_post_count" type="number" min="0"
                                   value="{{ old('desired_post_count') }}"
                                   class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            @error('desired_post_count')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- ← ここが重要：保存ボタン ＋ キャンセル --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('client.dashboard') }}"
                           class="px-4 py-2 text-sm text-gray-600 hover:underline">
                            キャンセル
                        </a>
                        <button type="submit"
                                class="px-5 py-2 text-sm font-semibold rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                            案件を作成する
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

