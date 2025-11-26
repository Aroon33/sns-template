<nav class="flex items-center justify-end gap-4">

    @auth
        @php($user = auth()->user())

        {{-- ユーザー種別ごとのリンク --}}
        @if ($user->role === 'client')
            <a href="{{ route('client.dashboard') }}" class="text-sm text-blue-600 hover:underline">
                クライアントページ
            </a>
        @elseif ($user->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-red-600 hover:underline">
                管理者ダッシュボード
            </a>
        @else
            <a href="{{ route('user.dashboard') }}" class="text-sm text-green-600 hover:underline">
                ユーザーページ
            </a>
        @endif

        {{-- ログアウト --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-600 hover:underline">
                ログアウト
            </button>
        </form>

    @else

        {{-- Xログイン --}}
        <a href="{{ route('auth.x.redirect') }}"
           class="px-4 py-1.5 text-xs font-semibold rounded-full bg-blue-600 text-white shadow hover:bg-blue-700">
            X でログイン
        </a>

        {{-- 通常ログイン --}}
        @if (Route::has('login'))
            <a href="{{ route('login') }}" class="text-sm hover:underline">
                Log in
            </a>
        @endif

        {{-- 新規登録（一般ユーザー） --}}
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="text-sm hover:underline">
                Register
            </a>
        @endif

    @endauth

</nav>
