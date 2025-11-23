{{-- resources/views/partials/nav.blade.php --}}

@php($user = auth()->user())

<div class="flex items-center justify-between">
    {{-- 左：ロゴ / ブランド --}}
    <a href="{{ url('/') }}" class="flex items-center gap-2">
        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#1b1b18] text-white text-xs font-semibold">
            MP
        </span>
        <span class="text-base font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
            MatchPost
        </span>
    </a>

    {{-- 右：ナビゲーション --}}
    <nav class="flex items-center gap-3">
        @auth
            {{-- ロール別リンク --}}
            @if ($user->role === 'client')
                <a href="{{ route('client.dashboard') }}"
                   class="px-4 py-1.5 text-xs font-medium rounded-full border border-[#19140035] dark:border-[#3E3E3A]
                          text-[#1b1b18] dark:text-[#EDEDEC] hover:border-[#1915014a] dark:hover:border-white">
                    クライアント
                </a>
            @else
                <a href="{{ route('user.dashboard') }}"
                   class="px-4 py-1.5 text-xs font-medium rounded-full border border-[#19140035] dark:border-[#3E3E3A]
                          text-[#1b1b18] dark:text-[#EDEDEC] hover:border-[#1915014a] dark:hover:border-white">
                    ユーザー
                </a>
            @endif

            @if ($user->role === 'admin')
                <a href="{{ route('admin.dashboard') }}"
                   class="px-4 py-1.5 text-xs font-medium rounded-full border border-pink-400 text-pink-700
                          bg-pink-50 hover:bg-pink-100">
                    管理者
                </a>
            @endif

            {{-- ログアウト --}}
            <form method="POST" action="{{ route('logout') }}" class="ml-2">
                @csrf
                <button type="submit"
                        class="px-4 py-1.5 text-xs font-medium rounded-full border border-transparent
                               text-gray-600 hover:border-[#19140035]">
                    ログアウト
                </button>
            </form>
        @else
            {{-- 未ログイン時：X でログイン + 通常ログイン/登録 --}}
            <a href="{{ route('auth.x.redirect') }}"
               class="px-4 py-1.5 text-xs font-semibold rounded-full bg-blue-600 text-white shadow">
                X でログイン
            </a>

            @if (Route::has('login'))
                <a href="{{ route('login') }}"
                   class="px-3 py-1.5 text-xs font-medium rounded-full text-[#1b1b18] dark:text-[#EDEDEC]
                          border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A]">
                    Log in
                </a>
            @endif

            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                   class="px-3 py-1.5 text-xs font-medium rounded-full text-[#1b1b18] dark:text-[#EDEDEC]
                          border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-white">
                    Register
                </a>
            @endif
        @endauth
    </nav>
</div>


