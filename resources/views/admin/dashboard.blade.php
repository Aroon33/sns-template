<x-app-layout>
    {{-- 上部ヘッダー --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                管理者ダッシュボード
            </h2>

            {{-- 管理トップナビ --}}
            <nav class="flex items-center gap-6 text-sm text-gray-600">
                <a href="{{ route('admin.dashboard') }}" class="hover:underline">
                    管理TOP
                </a>
                <a href="{{ route('admin.users.index') }}" class="hover:underline">
                    ユーザー一覧
                </a>
                <a href="{{ route('admin.campaigns.index') }}" class="hover:underline">
                    案件一覧
                </a>
                <a href="{{ route('admin.targets.index') }}" class="hover:underline">
                    ターゲット一覧
                </a>
                <a href="{{ route('client.dashboard') }}" class="hover:underline">
                    クライアント画面
                </a>
                <a href="{{ route('user.dashboard') }}" class="hover:underline">
                    ユーザー画面
                </a>
            </nav>
        </div>
    </x-slot>


    {{-- メインコンテンツ --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- サマリーカード 3列 --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- ユーザー数 --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">ユーザー数</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ $usersCount }}</p>
                    <a href="{{ route('admin.users.index') }}"
                       class="inline-block mt-4 text-sm text-blue-600 hover:underline">
                        → ユーザー一覧へ
                    </a>
                </div>

                {{-- 案件数 --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">案件数（キャンペーン）</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ $campaignsCount }}</p>
                    <a href="{{ route('admin.campaigns.index') }}"
                       class="inline-block mt-4 text-sm text-blue-600 hover:underline">
                        → 案件一覧へ
                    </a>
                </div>

                {{-- ターゲット数 --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">ターゲット数</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ $targetsCount }}</p>
                    <a href="{{ route('admin.targets.index') }}"
                       class="inline-block mt-4 text-sm text-blue-600 hover:underline">
                        → ターゲット一覧へ
                    </a>
                </div>

            </div>

            {{-- 説明エリア --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-700 leading-relaxed">
                    管理者ログイン成功！<br>
                    上のカードから「ユーザー一覧」「案件一覧」「ターゲット一覧」へ移動できます。<br>
                    今後ここに、グラフ・統計・最新アクティビティログ・AIアナリティクスなどを追加できます。
                </p>
            </div>

        </div>
    </div>

</x-app-layout>




