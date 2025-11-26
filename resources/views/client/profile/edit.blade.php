{{-- resources/views/client/profile/edit.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                クライアントプロフィール
            </h2>
            <a href="{{ route('client.dashboard') }}"
               class="px-4 py-2 text-sm rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 border">
                ← ダッシュボードに戻る
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-8 space-y-6">

                @if (session('status'))
                    <div class="p-3 bg-emerald-50 text-emerald-700 border border-emerald-300 rounded-md text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-4 bg-red-50 border border-red-300 rounded-md text-sm text-red-600">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('client.profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    {{-- 基本情報 --}}
                    <div>
                        <h3 class="text-base font-semibold text-gray-800 mb-2">基本情報</h3>

                        <label class="block text-sm font-medium text-gray-700 mb-1" for="name">
                            表示名 <span class="text-red-500">*</span>
                        </label>
                        <input id="name" name="name" type="text"
                               value="{{ old('name', $profile->name ?? $user->name) }}"
                               class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">
                            管理画面や依頼先に表示されるクライアント名です。
                        </p>
                    </div>

                    {{-- 種別 --}}
                    <div>
                        <h3 class="text-base font-semibold text-gray-800 mb-2">種別</h3>
                        <div class="flex items-center gap-6 text-sm">
                            <label class="inline-flex items-center">
                                <input type="radio" name="type" value="individual"
                                       class="rounded border-gray-300 text-indigo-600"
                                       {{ old('type', $profile->type) !== 'corp' ? 'checked' : '' }}>
                                <span class="ms-2">個人</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="type" value="corp"
                                       class="rounded border-gray-300 text-indigo-600"
                                       {{ old('type', $profile->type) === 'corp' ? 'checked' : '' }}>
                                <span class="ms-2">法人</span>
                            </label>
                        </div>
                    </div>

                    {{-- 会社情報 --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">
                                会社名
                            </label>
                            <input id="company_name" name="company_name" type="text"
                                   value="{{ old('company_name', $profile->company_name) }}"
                                   class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-1">
                                部署
                            </label>
                            <input id="department" name="department" type="text"
                                   value="{{ old('department', $profile->department) }}"
                                   class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    {{-- 連絡先 --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">
                                連絡用メールアドレス <span class="text-red-500">*</span>
                            </label>
                            <input id="contact_email" name="contact_email" type="email"
                                   value="{{ old('contact_email', $profile->contact_email ?? $user->email) }}"
                                   class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="contact_tel" class="block text-sm font-medium text-gray-700 mb-1">
                                電話番号
                            </label>
                            <input id="contact_tel" name="contact_tel" type="text"
                                   value="{{ old('contact_tel', $profile->contact_tel) }}"
                                   class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    {{-- 説明 --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            取扱い商材・依頼内容の説明
                        </label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $profile->description) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            案件依頼時にインフルエンサー側が確認する想定の説明文です。
                        </p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('client.dashboard') }}"
                           class="px-4 py-2 text-sm text-gray-600 hover:underline">
                            キャンセル
                        </a>
                        <button type="submit"
                                class="px-6 py-2 text-sm font-semibold rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                            保存する
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
