<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                クライアント会員登録
            </h2>
            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                クライアント用
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 text-sm text-red-600">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('client.register.store') }}" class="space-y-6">
                    @csrf

                    {{-- ログイン情報 --}}
                    <div class="border-b pb-4 mb-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">ログイン情報</h3>

                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">メールアドレス</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">パスワード</label>
                                <input type="password" name="password"
                                       class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">パスワード（確認）</label>
                                <input type="password" name="password_confirmation"
                                       class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    {{-- クライアント基本情報 --}}
                    <div class="border-b pb-4 mb-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">クライアント情報</h3>

                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    お名前 / 会社名
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                       class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    区分
                                </label>
                                <div class="flex items-center gap-4 text-sm">
                                    <label class="inline-flex items-center gap-1">
                                        <input type="radio" name="type" value="individual"
                                               {{ old('type', 'individual') === 'individual' ? 'checked' : '' }}>
                                        <span>個人</span>
                                    </label>
                                    <label class="inline-flex items-center gap-1">
                                        <input type="radio" name="type" value="corp"
                                               {{ old('type') === 'corp' ? 'checked' : '' }}>
                                        <span>法人</span>
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">会社名（任意）</label>
                                    <input type="text" name="company_name" value="{{ old('company_name') }}"
                                           class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">部署名・役職（任意）</label>
                                    <input type="text" name="department" value="{{ old('department') }}"
                                           class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">連絡先メール（任意）</label>
                                    <input type="email" name="contact_email" value="{{ old('contact_email') }}"
                                           class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">電話番号（任意）</label>
                                    <input type="text" name="contact_tel" value="{{ old('contact_tel') }}"
                                           class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    仕事内容・取り扱い商材など（任意）
                                </label>
                                <textarea name="description" rows="4"
                                          class="w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ url('/') }}" class="px-4 py-2 text-sm text-gray-600 hover:underline">
                            戻る
                        </a>
                        <button type="submit"
                                class="px-5 py-2 text-sm font-semibold rounded-md bg-blue-600 text-white hover:bg-blue-700">
                            クライアント登録する
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
