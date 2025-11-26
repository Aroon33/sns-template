{{-- resources/views/admin/api_posts/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                API投稿一覧
            </h2>

            <a href="{{ route('admin.api_posts.create') }}"
               class="px-4 py-2 text-sm rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                ＋ API投稿作成
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">

                @if (session('status'))
                    <div class="p-3 bg-emerald-50 text-emerald-700 border border-emerald-300 rounded-md text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($posts->isEmpty())
                    <p class="text-sm text-gray-500">
                        まだAPI投稿はありません。
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b text-gray-500">
                                    <th class="px-3 py-2 text-left">ID</th>
                                    <th class="px-3 py-2 text-left">タイトル</th>
                                    <th class="px-3 py-2 text-left">対象ユーザー数</th>
                                    <th class="px-3 py-2 text-left">作成日</th>
                                    <th class="px-3 py-2 text-left">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($posts as $post)
                                    <tr class="border-b last:border-0">
                                        <td class="px-3 py-2">{{ $post->id }}</td>
                                        <td class="px-3 py-2">{{ $post->title }}</td>
                                        <td class="px-3 py-2">{{ $post->targets->count() }}</td>
                                        <td class="px-3 py-2">{{ $post->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="px-3 py-2">
                                            <a href="{{ route('admin.api_posts.show', $post) }}"
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
                        {{ $posts->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
