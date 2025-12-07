{{-- resources/views/admin/x_actions/post/create.blade.php --}}
<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📝 投稿作成（X風UI）
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- 投稿確認ポップアップ（JSで制御） --}}
            <div id="confirmModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-6 rounded-md shadow-xl max-w-sm w-full">
                    <p class="text-gray-800 font-semibold mb-4">この内容で投稿しますか？</p>

                    <div class="flex justify-end gap-3">
                        <button onclick="closeConfirm()"
                                class="px-4 py-2 bg-gray-300 rounded-md text-gray-700 hover:bg-gray-400">
                            キャンセル
                        </button>

                        <button onclick="submitForm()"
                                class="px-4 py-2 bg-indigo-600 rounded-md text-white hover:bg-indigo-700">
                            投稿する
                        </button>
                    </div>
                </div>
            </div>


            {{-- 投稿フォーム --}}
            <form id="postForm" method="POST" action="{{ route('admin.x.post.execute') }}" enctype="multipart/form-data">
                @csrf

                {{-- 対象ユーザーの hidden --}}
                @foreach ($selectedUsers as $id)
                    <input type="hidden" name="user_ids[]" value="{{ $id }}">
                @endforeach

                {{-- 対象ユーザーの簡易表示 --}}
                <div class="mb-6 bg-white shadow-sm rounded-lg p-4">
                    <h3 class="font-semibold text-gray-700 mb-2">対象ユーザー</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($selectedUsers as $id)
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs">
                                ユーザーID: {{ $id }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- 投稿UI --}}
                <div class="bg-white shadow-sm rounded-lg p-6 space-y-6">

                    {{-- 見出し（タイトル） --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">見出し（任意）</label>
                        <input type="text" name="heading"
                               class="w-full border-gray-300 rounded-md focus:border-indigo-500 text-sm"
                               placeholder="投稿タイトル…">
                    </div>

                    {{-- 本文（X風エリア + 文字数カウント） --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">本文 <span class="text-red-500">*</span></label>

                        <textarea name="body" id="bodyText" rows="5"
                                  class="w-full border-gray-300 rounded-md focus:border-indigo-500 text-sm p-3"
                                  placeholder="いまどうしてる？（最大140文字）"></textarea>

                        <div class="text-right text-sm mt-1">
                            <span id="charCount">0</span> / 140
                        </div>
                    </div>

                    {{-- ハッシュタグ --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ハッシュタグ（任意）</label>
                        <input type="text" name="hashtags" id="hashtagsInput"
                               class="w-full border-gray-300 rounded-md focus:border-indigo-500 text-sm"
                               placeholder="#PR #キャンペーン">
                    </div>

                    {{-- 画像選択（1枚 or 複数対応可） --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">画像</label>
                        <input type="file" name="images[]" multiple
                               class="text-sm border-gray-300 rounded-md">
                    </div>

                    {{-- 投稿ボタン --}}
                    <div class="flex justify-end">
                        <button type="button"
                                onclick="openConfirm()"
                                class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            投稿内容を確認する
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    {{-- JS：投稿確認モーダル --}}
    <script>
        function openConfirm() {
            document.getElementById('confirmModal').classList.remove('hidden');
        }
        function closeConfirm() {
            document.getElementById('confirmModal').classList.add('hidden');
        }
        function submitForm() {
            document.getElementById('postForm').submit();
        }

        // 文字数カウント
        const textarea = document.getElementById('bodyText');
        const counter  = document.getElementById('charCount');

        textarea.addEventListener('input', function () {
            counter.textContent = textarea.value.length;
            if (textarea.value.length > 140) {
                counter.classList.add('text-red-600', 'font-semibold');
            } else {
                counter.classList.remove('text-red-600', 'font-semibold');
            }
        });
    </script>

</x-app-layout>
