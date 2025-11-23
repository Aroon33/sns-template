<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            対象ユーザー一覧：{{ $campaign->title }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4 text-sm text-gray-600">
                <p>
                    ステータス：
                    <span class="font-semibold">{{ $campaign->status }}</span>
                </p>
                <p>
                    フォロワー条件：
                    <span class="font-semibold">
                        {{ $min ?? 0 }} 〜 {{ $max ?? '制限なし' }}
                    </span>
                </p>
                <p class="mt-1">
                    対象ジャンル：
                    @if (!empty($genreIds))
                        @foreach ($campaign->genres as $genre)
                            <span class="inline-block px-2 py-1 bg-gray-100 rounded text-xs text-gray-700">
                                {{ $genre->name }}
                            </span>
                        @endforeach
                    @else
                        <span class="text-gray-500">制限なし</span>
                    @endif
                </p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- 一括追加フォーム --}}
                <form method="post" action="{{ route('admin.campaigns.targets.bulk_add', $campaign) }}">
                    @csrf

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <label class="inline-flex items-center text-sm text-gray-700">
                                <input type="checkbox" id="check_all" class="mr-1">
                                <span>全選択</span>
                            </label>
                        </div>
                        <div>
                            <button type="submit"
                                class="px-4 py-2 text-xs font-semibold bg-indigo-600 text-white rounded">
                                選択したユーザーを一括追加
                            </button>
                        </div>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">
                                    選択
                                </th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">
                                    XユーザーID
                                </th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">
                                    登録名
                                </th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">名前</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">メール</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">ジャンル</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">地域</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">フォロワー数</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">状態</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($users as $user)
                                @php
                                    $isAdded = in_array($user->id, $addedUserIds);
                                    $profile = $user->profile;
                                @endphp

                                <tr>
                                    {{-- チェックボックス --}}
                                    <td class="px-4 py-2">
                                        @if ($isAdded)
                                            <input type="checkbox"
                                                disabled
                                                class="text-gray-300 border-gray-200 rounded">
                                        @else
                                            <input type="checkbox"
                                                name="user_ids[]"
                                                value="{{ $user->id }}"
                                                class="user-checkbox text-indigo-600 border-gray-300 rounded">
                                        @endif
                                    </td>

                                    {{-- XユーザーID --}}
                                    <td class="px-4 py-2">
                                        {{ $profile->x_user_id ?? '-' }}
                                    </td>

                                    {{-- 登録名（漢字） --}}
                                    <td class="px-4 py-2">
                                        {{ $profile->display_name ?? '-' }}
                                    </td>

                                    <td class="px-4 py-2">{{ $user->id }}</td>
                                    <td class="px-4 py-2">{{ $user->name }}</td>
                                    <td class="px-4 py-2">{{ $user->email }}</td>

                                    <td class="px-4 py-2">
                                        @foreach ($user->genres as $genre)
                                            <span class="inline-block px-2 py-1 bg-gray-100 rounded text-xs text-gray-700">
                                                {{ $genre->name }}
                                            </span>
                                        @endforeach
                                    </td>

                                    <td class="px-4 py-2">{{ $profile->location ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $profile->followers_count ?? 0 }}</td>

                                    <td class="px-4 py-2">
                                        @if ($isAdded)
                                            <span class="px-3 py-1 text-xs bg-gray-300 text-gray-700 rounded">
                                                追加済み
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded">
                                                未追加
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-4 py-4 text-center text-gray-500">
                                        条件に一致するユーザーがいません。
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </form>

            </div>

        </div>
    </div>

    {{-- 全選択用 JS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkAll = document.getElementById('check_all');
            const checkboxes = document.querySelectorAll('.user-checkbox');

            if (checkAll) {
                checkAll.addEventListener('change', function () {
                    checkboxes.forEach(cb => {
                        cb.checked = checkAll.checked);
                    });
                });
            }
        });
    </script>
</x-app-layout>



