{{-- resources/views/admin/campaign_targets/all.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                全ターゲット一覧
            </h2>

            <a href="{{ route('admin.dashboard') }}"
               class="px-4 py-2 text-sm rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 border">
                ← 管理ダッシュボードへ戻る
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if ($targets->isEmpty())
                    <p class="text-sm text-gray-500">
                        ターゲットはまだ登録されていません。
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                            <tr class="border-b text-gray-500">
                                <th class="px-3 py-2 text-left">ID</th>
                                <th class="px-3 py-2 text-left">ユーザー</th>
                                <th class="px-3 py-2 text-left">Xアカウント</th>
                                <th class="px-3 py-2 text-left">キャンペーン</th>
                                <th class="px-3 py-2 text-left">ステータス</th>
                                <th class="px-3 py-2 text-left">作成日</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($targets as $target)
                                <tr class="border-b last:border-0">
                                    <td class="px-3 py-2">
                                        {{ $target->id }}
                                    </td>
                                    <td class="px-3 py-2">
                                        {{ $target->user?->name }}
                                    </td>
                                    <td class="px-3 py-2">
                                        @if (!empty($target->user?->profile?->x_username))
                                            @{{ $target->user->profile->x_username }}
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">
                                        {{ $target->campaign?->title ?? '(不明なキャンペーン)' }}
                                    </td>
                                    <td class="px-3 py-2">
                                        {{ $target->status }}
                                    </td>
                                    <td class="px-3 py-2">
                                        {{ $target->created_at?->format('Y-m-d') }}
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
