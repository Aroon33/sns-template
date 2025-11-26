{{-- resources/views/admin/users/mypage.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ユーザー詳細（管理者）
            </h2>

            <a href="{{ route('admin.users.index') }}"
               class="px-4 py-2 text-sm rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 border">
                ← ユーザー一覧に戻る
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- ステータス --}}
            @if (session('status'))
                <div class="p-3 bg-emerald-50 text-emerald-700 border border-emerald-300 rounded-md text-sm">
                    {{ session('status') }}
                </div>
            @endif

            {{-- 基本情報 + X情報 --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-800">ユーザー情報</h3>

                <div class="flex flex-col md:flex-row md:items-center gap-6">
                    <div class="flex items-center gap-4">
                        @if (!empty(optional($profile)->avatar_url))
                            <img src="{{ optional($profile)->avatar_url }}"
                                 alt="avatar"
                                 class="w-16 h-16 rounded-full border object-cover">
                        @else
                            <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                {{ mb_substr($user->name, 0, 1) }}
                            </div>
                        @endif

                        <div>
                            <p class="text-base font-semibold text-gray-900">
                                {{ optional($profile)->display_name ?? $user->name }}
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                    role: {{ $user->role }}
                                </span>
                            </p>
                            @if (!empty(optional($profile)->x_username))
                                <p class="text-sm text-gray-500">
                                    @{{ optional($profile)->x_username }}
                                </p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">
                                email: {{ $user->email }} / 登録日: {{ $user->created_at?->format('Y-m-d') }}
                            </p>
                        </div>
                    </div>

                    {{-- メトリクス --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm md:ms-auto">
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
                        <div class="md:col-span-2">
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

            {{-- アクション登録フォーム --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-800">管理者アクションを登録</h3>
                <p class="text-sm text-gray-500">
                    このユーザーに対して依頼したいアクション（投稿依頼・いいね依頼・DM など）を登録します。
                </p>

                <form method="POST" action="{{ route('admin.users.actions.store', $user) }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                                アクション種別 <span class="text-red-500">*</span>
                            </label>
                            <select id="type" name="type"
                                    class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="post_request">投稿依頼</option>
                                <option value="like_request">いいね依頼</option>
                                <option value="rt_request">リツイート依頼</option>
                                <option value="dm">DM 送信依頼</option>
                                <option value="other">その他</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="note" class="block text-sm font-medium text-gray-700 mb-1">
                                メモ（内容・URL・条件など）
                            </label>
                            <textarea id="note" name="note" rows="3"
                                      class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="例：〇〇キャンペーンのLPを投稿してもらう、指定ハッシュタグ：#xxx など">{{ old('note') }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-6 py-2 text-sm font-semibold rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                            アクションを登録する
                        </button>
                    </div>
                </form>
            </div>

            {{-- アクション履歴 --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-800">アクション履歴</h3>

                @if ($actions->isEmpty())
                    <p class="text-sm text-gray-500">
                        まだアクションは登録されていません。
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                            <tr class="border-b text-gray-500">
                                <th class="px-3 py-2 text-left">日時</th>
                                <th class="px-3 py-2 text-left">種別</th>
                                <th class="px-3 py-2 text-left">ステータス</th>
                                <th class="px-3 py-2 text-left">メモ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($actions as $action)
                                <tr class="border-b last:border-0 align-top">
                                    <td class="px-3 py-2">
                                        {{ $action->created_at?->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="px-3 py-2">
                                        @switch($action->type)
                                            @case('post_request') 投稿依頼 @break
                                            @case('like_request') いいね依頼 @break
                                            @case('rt_request') リツイート依頼 @break
                                            @case('dm') DM依頼 @break
                                            @default その他
                                        @endswitch
                                    </td>
                                    <td class="px-3 py-2">
                                        {{ $action->status }}
                                    </td>
                                    <td class="px-3 py-2 whitespace-pre-line">
                                        {{ $action->note }}
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
</x-app-layout>



