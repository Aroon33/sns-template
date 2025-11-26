{{-- resources/views/user/mypage.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ユーザーマイページ
            </h2>

            <a href="{{ route('user.dashboard') }}"
               class="px-4 py-2 text-sm rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 border">
                ← ユーザーダッシュボードへ戻る
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 完了メッセージ --}}
            @if (session('status'))
                <div class="p-3 bg-emerald-50 text-emerald-700 border border-emerald-300 rounded-md text-sm">
                    {{ session('status') }}
                </div>
            @endif

            {{-- エラー --}}
            @if ($errors->any())
                <div class="p-4 bg-red-50 border border-red-300 rounded-md text-sm text-red-600">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 基本情報 + X情報 --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
                <h3 class="text-lg font-semibold text-gray-800">基本情報</h3>

                <div class="flex flex-col md:flex-row md:items-center gap-6">
                    <div class="flex items-center gap-4">
                        @if (!empty($profile->avatar_url))
                            <img src="{{ $profile->avatar_url }}"
                                 alt="avatar"
                                 class="w-16 h-16 rounded-full border object-cover">
                        @else
                            <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                {{ mb_substr($user->name, 0, 1) }}
                            </div>
                        @endif

                        <div>
                            <p class="text-base font-semibold text-gray-900">
                                {{ $profile->display_name ?? $user->name }}
                            </p>
                            @if (!empty($profile->x_username))
                                <p class="text-sm text-gray-500">
                                    @{{ $profile->x_username }}
                                </p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">
                                登録メール：{{ $user->email }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm md:ms-auto">
                        <div>
                            <p class="text-xs text-gray-500">フォロワー</p>
                            <p class="text-lg font-semibold">
                                {{ number_format($metrics->followers_count ?? 0) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">投稿数</p>
                            <p class="text-lg font-semibold">
                                {{ number_format($metrics->posts_count ?? 0) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">いいね数</p>
                            <p class="text-lg font-semibold text-rose-600">
                                {{ number_format($metrics->likes_count ?? 0) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">リツイート数</p>
                            <p class="text-lg font-semibold text-sky-600">
                                {{ number_format($metrics->retweets_count ?? 0) }}
                            </p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs text-gray-500">インプレッション</p>
                            <p class="text-lg font-semibold text-gray-800">
                                {{ number_format($metrics->impressions_count ?? 0) }}
                            </p>
                        </div>
                    </div>
                </div>

                <p class="text-xs text-gray-500">
                    ※ 数値は今後 X API と連携して自動更新する想定です（現状はプレースホルダー）。
                </p>
            </div>

            {{-- 銀行情報 --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-800">報酬受取口座（銀行情報）</h3>
                <p class="text-sm text-gray-500">
                    報酬の振込先となる銀行口座情報を登録します。
                </p>

                <form method="POST" action="{{ route('user.mypage.bank.update') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-1">
                                銀行名
                            </label>
                            <input id="bank_name" name="bank_name" type="text"
                                   value="{{ old('bank_name', $profile->bank_name) }}"
                                   class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="bank_branch" class="block text-sm font-medium text-gray-700 mb-1">
                                支店名
                            </label>
                            <input id="bank_branch" name="bank_branch" type="text"
                                   value="{{ old('bank_branch', $profile->bank_branch) }}"
                                   class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="bank_account_type" class="block text-sm font-medium text-gray-700 mb-1">
                                口座種別
                            </label>
                            <input id="bank_account_type" name="bank_account_type" type="text"
                                   placeholder="普通 / 当座 など"
                                   value="{{ old('bank_account_type', $profile->bank_account_type) }}"
                                   class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="bank_account_number" class="block text-sm font-medium text-gray-700 mb-1">
                                口座番号
                            </label>
                            <input id="bank_account_number" name="bank_account_number" type="text"
                                   value="{{ old('bank_account_number', $profile->bank_account_number) }}"
                                   class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="md:col-span-2">
                            <label for="bank_account_holder" class="block text-sm font-medium text-gray-700 mb-1">
                                口座名義人
                            </label>
                            <input id="bank_account_holder" name="bank_account_holder" type="text"
                                   value="{{ old('bank_account_holder', $profile->bank_account_holder) }}"
                                   class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-xs text-gray-500 mt-1">
                                カタカナ入力が必要な場合があります（例：ヤマダ タロウ）。
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="submit"
                                class="px-6 py-2 text-sm font-semibold rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                            銀行情報を保存する
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>