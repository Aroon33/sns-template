{{-- resources/views/admin/api_posts/create.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                API投稿作成
            </h2>

            <a href="{{ route('admin.dashboard') }}"
               class="px-4 py-2 text-sm rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 border">
                ← 管理ダッシュボードへ戻る
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">

                {{-- エラー表示 --}}
                @if ($errors->any())
                    <div class="p-4 bg-red-50 border border-red-300 rounded-md text-sm text-red-700">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('admin.api_posts.store') }}"
                      enctype="multipart/form-data"
                      class="space-y-8">
                    @csrf

                    {{-- タイトル --}}
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                            タイトル（管理用） <span class="text-red-500">*</span>
                        </label>
                        <input id="title" name="title" type="text"
                               value="{{ old('title') }}"
                               class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    {{-- 本文 --}}
                    <div>
                        <label for="body" class="block text-sm font-medium text-gray-700 mb-1">
                            投稿本文 <span class="text-red-500">*</span>
                        </label>
                        <textarea id="body" name="body" rows="5"
                                  class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="実際にXに投稿する本文を入力してください。">{{ old('body') }}</textarea>
                    </div>

                    {{-- ハッシュタグ --}}
                    <div>
                        <label for="hashtags" class="block text-sm font-medium text-gray-700 mb-1">
                            ハッシュタグ
                        </label>
                        <input id="hashtags" name="hashtags" type="text"
                               value="{{ old('hashtags') }}"
                               class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="#example #campaign など">
                        <p class="text-xs text-gray-500 mt-1">
                            半角スペース区切りなど、お好きな形式で。送信時に整形する想定です。
                        </p>
                    </div>

                    {{-- 画像 --}}
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">
                            画像
                        </label>
                        <input id="image" name="image" type="file"
                               class="text-sm text-gray-700">
                        <p class="text-xs text-gray-500 mt-1">
                            任意。5MB以下の画像ファイル（jpg, pngなど）。
                        </p>
                    </div>

                    {{-- 対象ユーザー選択 --}}
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-800">
                                対象ユーザー選択 <span class="text-red-500">*</span>
                            </h3>
                            <button type="button"
                                    onclick="toggleAllUsers(true)"
                                    class="text-xs text-indigo-600 hover:underline">
                                すべて選択
                            </button>
                            <button type="button"
                                    onclick="toggleAllUsers(false)"
                                    class="text-xs text-gray-500 hover:underline ml-2">
                                すべて解除
                            </button>
                        </div>

                        <div class="border rounded-md max-h-72 overflow-y-auto">
                            <table class="min-w-full text-xs">
                                <thead>
                                <tr class="border-b bg-gray-50 text-gray-500">
                                    <th class="px-3 py-2 text-left">選択</th>
                                    <th class="px-3 py-2 text-left">ID</th>
                                    <th class="px-3 py-2 text-left">名前</th>
                                    <th class="px-3 py-2 text-left">Xアカウント</th>
                                    <th class="px-3 py-2 text-left">フォロワー</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($users as $u)
                                    <tr class="border-b last:border-0">
                                        <td class="px-3 py-2">
                                            <input type="checkbox"
                                                   name="user_ids[]"
                                                   value="{{ $u->id }}"
                                                   class="user-checkbox rounded border-gray-300 text-indigo-600"
                                                   {{ in_array($u->id, old('user_ids', [])) ? 'checked' : '' }}>
                                        </td>
                                        <td class="px-3 py-2">{{ $u->id }}</td>
                                        <td class="px-3 py-2">{{ $u->name }}</td>
                                        <td class="px-3 py-2">
                                            @if (!empty($u->profile?->x_username))
                                                @{{ $u->profile->x_username }}
                                            @endif
                                        </td>
                                        <td class="px-3 py-2">
                                            {{ number_format($u->profile->followers_count ?? 0) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-3 py-4 text-center text-gray-500">
                                            対象ユーザーがまだ登録されていません。
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- 送信ボタン --}}
                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-6 py-2 text-sm font-semibold rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                            API投稿リクエストを登録する
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 簡単な「全選択・解除」スクリプト --}}
    <script>
        function toggleAllUsers(checked) {
            document.querySelectorAll('.user-checkbox').forEach(cb => {
                cb.checked = checked;
            });
        }
    </script>
</x-app-layout>
