{{-- resources/views/admin/api_posts/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                API投稿詳細 #{{ $post->id }}
            </h2>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.api_posts.index') }}"
                   class="px-4 py-2 text-sm rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 border">
                    ← API投稿一覧に戻る
                </a>
                <a href="{{ route('admin.api_posts.create') }}"
                   class="px-4 py-2 text-sm rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                    ＋ 新規API投稿作成
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 投稿内容 --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-800">投稿内容</h3>

                <p class="text-sm text-gray-500">
                    作成者：{{ $post->creator?->name ?? '-' }}
                    ／ 作成日：{{ $post->created_at?->format('Y-m-d H:i') }}
                </p>

                <div>
                    <p class="text-sm font-medium text-gray-700">タイトル</p>
                    <p class="text-base text-gray-900">
                        {{ $post->title }}
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-700">本文</p>
                    <pre class="text-sm text-gray-900 whitespace-pre-wrap bg-gray-50 rounded-md p-3">
{{ $post->body }}</pre>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-700">ハッシュタグ</p>
                    <p class="text-sm text-gray-900">
                        {{ $post->hashtags ?: '（なし）' }}
                    </p>
                </div>

                @if ($post->image_path)
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-1">画像</p>
                        <img src="{{ asset('storage/'.$post->image_path) }}"
                             alt="API Post Image"
                             class="max-h-64 rounded-md border">
                    </div>
                @endif
            </div>

            {{-- 対象ユーザー一覧 --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-800">対象ユーザー</h3>

                @if ($post->targets->isEmpty())
                    <p class="text-sm text-gray-500">
                        対象ユーザーが登録されていません。
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                            <tr class="border-b text-gray-500">
                                <th class="px-3 py-2 text-left">ユーザーID</th>
                                <th class="px-3 py-2 text-left">名前</th>
                                <th class="px-3 py-2 text-left">Xアカウント</th>
                                <th class="px-3 py-2 text-left">ステータス</th>
                                <th class="px-3 py-2 text-left">登録日時</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($post->targets as $target)
                                <tr class="border-b last:border-0">
                                    <td class="px-3 py-2">{{ $target->user?->id }}</td>
                                    <td class="px-3 py-2">{{ $target->user?->name }}</td>
                                    <td class="px-3 py-2">
                                        @if (!empty($target->user?->profile?->x_username))
                                            @{{ $target->user->profile->x_username }}
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">
                                        @switch($target->status)
                                            @case('pending')
                                                <span class="px-2 py-1 text-xs rounded bg-amber-50 text-amber-700 border border-amber-200">
                                                    pending
                                                </span>
                                                @break
                                            @case('sent')
                                                <span class="px-2 py-1 text-xs rounded bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    sent
                                                </span>
                                                @break
                                            @case('failed')
                                                <span class="px-2 py-1 text-xs rounded bg-rose-50 text-rose-700 border border-rose-200">
                                                    failed
                                                </span>
                                                @break
                                            @default
                                                {{ $target->status }}
                                        @endswitch
                                    </td>
                                    <td class="px-3 py-2">
                                        {{ $target->created_at?->format('Y-m-d H:i') }}
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

