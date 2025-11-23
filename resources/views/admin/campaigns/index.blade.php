<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            案件一覧
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4">
                <a href="{{ route('admin.campaigns.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                    新規案件を作成
                </a>
            </div>

            @if (session('status'))
                <div class="mb-4 text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">タイトル</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">ステータス</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">対象ジャンル</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">フォロワー条件</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">期間</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($campaigns as $campaign)
                            <tr>
                                <td class="px-4 py-2">{{ $campaign->id }}</td>
                                <td class="px-4 py-2">{{ $campaign->title }}</td>
                                <td class="px-4 py-2">{{ $campaign->status }}</td>
                                <td class="px-4 py-2">
                                    @foreach ($campaign->genres as $genre)
                                        <span class="inline-block px-2 py-1 bg-gray-100 rounded text-xs text-gray-700">
                                            {{ $genre->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-4 py-2">
                                    {{ $campaign->min_followers ?? 0 }} 〜 {{ $campaign->max_followers ?? '制限なし' }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ optional($campaign->start_at)->format('Y-m-d') ?? '-' }}
                                     〜
                                    {{ optional($campaign->end_at)->format('Y-m-d') ?? '-' }}
                                </td>
                                <td class="px-4 py-2 text-right">
                                    <a href="{{ route('admin.campaigns.edit', $campaign) }}"
                                       class="text-indigo-600 hover:text-indigo-900 text-xs">編集</a>

                                    <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="post" class="inline-block ml-2"
                                          onsubmit="return confirm('本当に削除しますか？');">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-xs">削除</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        @if ($campaigns->isEmpty())
                            <tr>
                                <td colspan="7" class="px-4 py-4 text-center text-gray-500">
                                    案件がまだ登録されていません。
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
