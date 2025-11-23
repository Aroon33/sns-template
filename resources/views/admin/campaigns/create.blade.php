<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            新規案件作成
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form method="post" action="{{ route('admin.campaigns.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="title" value="タイトル" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                      :value="old('title')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <x-input-label for="description" value="案件説明" />
                        <textarea id="description" name="description" rows="3"
                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div>
                        <x-input-label for="post_text" value="投稿本文（テンプレート）" />
                        <textarea id="post_text" name="post_text" rows="4"
                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('post_text') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('post_text')" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="min_followers" value="最小フォロワー数" />
                            <x-text-input id="min_followers" name="min_followers" type="number" min="0"
                                          class="mt-1 block w-full" :value="old('min_followers')" />
                            <x-input-error class="mt-2" :messages="$errors->get('min_followers')" />
                        </div>
                        <div>
                            <x-input-label for="max_followers" value="最大フォロワー数" />
                            <x-text-input id="max_followers" name="max_followers" type="number" min="0"
                                          class="mt-1 block w-full" :value="old('max_followers')" />
                            <x-input-error class="mt-2" :messages="$errors->get('max_followers')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="start_at" value="開始日" />
                            <x-text-input id="start_at" name="start_at" type="date"
                                          class="mt-1 block w-full" :value="old('start_at')" />
                            <x-input-error class="mt-2" :messages="$errors->get('start_at')" />
                        </div>
                        <div>
                            <x-input-label for="end_at" value="終了日" />
                            <x-text-input id="end_at" name="end_at" type="date"
                                          class="mt-1 block w-full" :value="old('end_at')" />
                            <x-input-error class="mt-2" :messages="$errors->get('end_at')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label value="対象ジャンル" />
                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach ($genres as $genre)
                                <label class="inline-flex items-center">
                                    <input type="checkbox"
                                           name="genre_ids[]"
                                           value="{{ $genre->id }}"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           @checked(in_array($genre->id, old('genre_ids', [])))>
                                    <span class="ms-2 text-sm text-gray-700">{{ $genre->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('genre_ids')" />
                    </div>

                    <div>
                        <x-input-label for="status" value="ステータス" />
                        <select id="status" name="status"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>下書き</option>
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>公開中</option>
                            <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>終了</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>保存</x-primary-button>
                        <a href="{{ route('admin.campaigns.index') }}" class="text-sm text-gray-600">一覧に戻る</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
