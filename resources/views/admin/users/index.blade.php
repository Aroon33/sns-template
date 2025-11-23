<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ユーザー一覧
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">名前</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">メール</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ジャンル</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">地域</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">フォロワー数</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-4 py-2">{{ $user->id }}</td>
                                <td class="px-4 py-2">{{ $user->name }}</td>
                                <td class="px-4 py-2">{{ $user->email }}</td>
                                <td class="px-4 py-2">
                                    @foreach ($user->genres as $genre)
                                        <span class="inline-block px-2 py-1 bg-gray-100 rounded text-xs text-gray-700">
                                            {{ $genre->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-4 py-2">{{ $user->profile->location ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $user->profile->followers_count ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
