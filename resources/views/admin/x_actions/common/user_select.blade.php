{{-- resources/views/admin/x_actions/common/user_select.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ ucfirst($actionName) }} 実行：対象ユーザー選択
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- フィルター部分 --}}
            <form method="GET" action="" class="mb-4 flex items-center gap-4">
                <div>
                    <label class="text-sm text-gray-600">登録日</label>
                    <select name="sort" onchange="this.form.submit()"
                        class="border-gray-300 rounded-md text-sm">
                        <option value="desc" {{ request('sort') === 'asc' ? '' : 'selected' }}>新しい順</option>
                        <option value="asc" {{ request('sort') === 'asc' ? 'selected' : '' }}>古い順</option>
                    </select>
                </div>
            </form>

            {{-- ユーザー選択フォーム（次ページへ POST） --}}
            <form method="POST" action="{{ $submitRoute }}">
                @csrf

                <div class="bg-white shadow-sm sm:rounded-lg p-6 overflow-x-auto">

                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b bg-gray-50 text-gray-500">
                                <th class="px-3 py-2 text-left">選択</th>
                                <th class="px-3 py-2 text-left">ID</th>
                                <th class="px-3 py-2 text-left">名前</th>
                                <th class="px-3 py-2 text-left">アカウント名</th>
                                <th class="px-3 py-2 text-left">アイコン</th>
                                <th class="px-3 py-2 text-right">フォロワー</th>
                                <th class="px-3 py-2 text-right">投稿数</th>
                                <th class="px-3 py-2 text-right">フォロー</th>
                                <th class="px-3 py-2 text-right">リスト数</th>
                                <th class="px-3 py-2 text-left">登録日</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($users as $u)
                                @php
                                    $p = $u->profile;
                                @endphp

                                <tr class="border-b last:border-0">
                                    <td class="px-3 py-2">
                                        <input type="checkbox"
                                               name="user_ids[]"
                                               value="{{ $u->id }}"
                                               class="rounded border-gray-300 text-indigo-600">
                                    </td>
                                    <td class="px-3 py-2">{{ $u->id }}</td>

                                    <td class="px-3 py-2">
                                        {{ $p->display_name ?? $u->name }}
                                    </td>

                                    <td class="px-3 py-2">
                                        @if ($p?->x_username)
                                            @{{ $p->x_username }}
                                        @else
                                            <span class="text-gray-400 text-xs">未連携</span>
                                        @endif
                                    </td>

                                    <td class="px-3 py-2">
                                        @if ($p?->avatar_url)
                                            <img src="{{ $p->avatar_url }}"
                                                 class="h-8 w-8 rounded-full">
                                        @else
                                            <span class="text-xs text-gray-400">なし</span>
                                        @endif
                                    </td>

                                    <td class="px-3 py-2 text-right">
                                        {{ number_format($p->followers_count ?? 0) }}
                                    </td>

                                    <td class="px-3 py-2 text-right">
                                        {{ number_format($p->tweet_count ?? 0) }}
                                    </td>

                                    <td class="px-3 py-2 text-right">
                                        {{ number_format($p->following_count ?? 0) }}
                                    </td>

                                    <td class="px-3 py-2 text-right">
                                        {{ number_format($p->listed_count ?? 0) }}
                                    </td>

                                    <td class="px-3 py-2">
                                        {{ $u->created_at?->format('Y-m-d') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- ページネーション --}}
                    <div class="mt-4">
                        {{ $users->appends(request()->query())->links() }}
                    </div>

                    {{-- 次へボタン --}}
                    <div class="flex justify-end mt-6">
                        <button type="submit"
                                class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            次へ（{{ ucfirst($actionName) }}を作成）
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

</x-app-layout>
