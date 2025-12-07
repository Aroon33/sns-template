{{-- resources/views/admin/users/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                登録ユーザー一覧（管理者）
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- サマリーカード --}}
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">登録ユーザー数</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($totalUsers) }}</p>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">総フォロワー数</p>
                    <p class="text-2xl font-bold text-indigo-600">
                        {{ number_format($metricsSummary->followers_sum ?? 0) }}
                    </p>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">総投稿数</p>
                    <p class="text-2xl font-bold text-indigo-600">
                        {{ number_format($metricsSummary->posts_sum ?? 0) }}
                    </p>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">総いいね数</p>
                    <p class="text-2xl font-bold text-rose-600">
                        {{ number_format($metricsSummary->likes_sum ?? 0) }}
                    </p>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-1">総インプレッション</p>
                    <p class="text-2xl font-bold text-emerald-600">
                        {{ number_format($metricsSummary->impressions_sum ?? 0) }}
                    </p>
                </div>

            </div>

            {{-- 登録ユーザー一覧テーブル --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    ユーザー一覧
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                        <tr class="border-b text-gray-500">
                            <th class="px-3 py-2 text-left">ID</th>
                            <th class="px-3 py-2 text-left">名前</th>
                            <th class="px-3 py-2 text-left">メール</th>
                            <th class="px-3 py-2 text-left">ロール</th>
                            <th class="px-3 py-2 text-left">Xアカウント</th>
                            <th class="px-3 py-2 text-right">フォロワー</th>
                            <th class="px-3 py-2 text-right">投稿数</th>
                            <th class="px-3 py-2 text-right">いいね</th>
                            <th class="px-3 py-2 text-right">RT</th>
                            <th class="px-3 py-2 text-right">インプレッション</th>
                            <th class="px-3 py-2 text-left">登録日</th>
                            <th class="px-3 py-2 text-left">詳細</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($users as $user)
                            @php
                                $profile = $user->profile;
                                $metric  = $user->metric;
                            @endphp

                            <tr class="border-b last:border-0">
                                <td class="px-3 py-2">{{ $user->id }}</td>
                                <td class="px-3 py-2">{{ $profile->display_name ?? $user->name }}</td>
                                <td class="px-3 py-2">{{ $user->email }}</td>
                                <td class="px-3 py-2">
                                    <span class="px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-700">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-3 py-2">
                                    @if ($profile?->x_username)
                                        @{{ $profile->x_username }}
                                    @else
                                        <span class="text-xs text-gray-400">未連携</span>
                                    @endif
                                </td>

                                <td class="px-3 py-2 text-right">
                                    {{ number_format($metric->followers_count ?? 0) }}
                                </td>
                                <td class="px-3 py-2 text-right">
                                    {{ number_format($metric->posts_count ?? 0) }}
                                </td>
                                <td class="px-3 py-2 text-right text-rose-600">
                                    {{ number_format($metric->likes_count ?? 0) }}
                                </td>
                                <td class="px-3 py-2 text-right text-sky-600">
                                    {{ number_format($metric->retweets_count ?? 0) }}
                                </td>
                                <td class="px-3 py-2 text-right text-indigo-600">
                                    {{ number_format($metric->impressions_count ?? 0) }}
                                </td>

                                <td class="px-3 py-2">
                                    {{ $user->created_at?->format('Y-m-d') }}
                                </td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="text-indigo-600 hover:underline">
                                        詳細
                                    </a>
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
