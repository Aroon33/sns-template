{{-- resources/views/admin/users/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ユーザー一覧（管理者）
            </h2>

            <nav class="flex items-center gap-4 text-sm text-gray-600">
                <a href="{{ route('admin.dashboard') }}" class="hover:underline">管理TOP</a>
                <a href="{{ route('admin.users.index') }}" class="font-semibold text-blue-600">ユーザー一覧</a>
                <a href="{{ route('admin.campaigns.index') }}" class="hover:underline">案件一覧</a>
            </nav>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- サマリー --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6 flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">登録ユーザー数</p>
                    <p class="text-3xl font-bold text-gray-800">
                        {{ method_exists($users, 'total') ? $users->total() : $users->count() }} 件
                    </p>
                </div>
            </div>

            {{-- ユーザー一覧テーブル --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-0 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 border-b">
                        <tr class="text-left text-gray-600 text-xs uppercase tracking-wider">
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">名前</th>
                            <th class="px-4 py-3">メール</th>
                            <th class="px-4 py-3">ロール</th>
                            <th class="px-4 py-3">X ユーザー名</th>
                            <th class="px-4 py-3">フォロワー</th>
                            <th class="px-4 py-3">登録日</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        @forelse ($users as $user)
                            @php($p = $user->profile)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $user->id }}</td>
                                <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2 py-0.5 text-xs rounded-full font-semibold
                                        @if($user->role === 'admin')
                                            bg-pink-100 text-pink-700
                                        @elseif($user->role === 'client')
                                            bg-blue-100 text-blue-700
                                        @else
                                            bg-green-100 text-green-700
                                        @endif
                                    ">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($p && $p->x_username)
                                        @{{ $p->x_username }}
                                    @else
                                        <span class="text-xs text-gray-400">未連携</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    {{ isset($p->followers_count) ? number_format($p->followers_count) : '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-500">
                                    {{ optional($user->created_at)->format('Y-m-d') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                    まだユーザーがいません。
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ページネーション --}}
                @if(method_exists($users, 'links'))
                    <div class="px-4 py-3 bg-gray-50 border-t">
                        {{ $users->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>

