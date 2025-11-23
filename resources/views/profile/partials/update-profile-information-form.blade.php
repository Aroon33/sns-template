<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            プロフィール情報
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            アカウント情報とプロフィールを更新できます。
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- 名前 -->
        <div>
            <x-input-label for="name" value="名前" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                :value="old('name', $user->name)" required autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- メール -->
        <div>
            <x-input-label for="email" value="メールアドレス" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                :value="old('email', $user->email)" required />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <!-- 地域 -->
        <div>
            <x-input-label for="location" value="地域" />
            <x-text-input id="location" name="location" type="text" class="mt-1 block w-full"
                :value="old('location', $profile->location ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('location')" />
        </div>

        <!-- 自己紹介 -->
        <div>
            <x-input-label for="bio" value="自己紹介" />
            <textarea id="bio" name="bio" rows="4"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('bio', $profile->bio ?? '') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <!-- 興味ジャンル -->
        <div>
            <x-input-label value="興味のあるジャンル" />
            <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                @foreach ($genres as $genre)
                    <label class="inline-flex items-center">
                        <input type="checkbox"
                               name="genre_ids[]"
                               value="{{ $genre->id }}"
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                               @checked(in_array($genre->id, old('genre_ids', $selectedGenreIds)))>
                        <span class="ms-2 text-sm text-gray-700">{{ $genre->name }}</span>
                    </label>
                @endforeach
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('genre_ids')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>保存する</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >保存しました。</p>
            @endif
        </div>
    </form>
</section>
