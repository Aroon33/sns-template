{{-- resources/views/user/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                マイページ（ユーザー）
            </h2>
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                ログイン中: {{ $user->name }}
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- プロフィールカード --}}
            <div class="bg-white sm:rounded-lg shadow-sm p-6 flex flex-col md:flex-row gap-6">
                <div class="flex-shrink-0">
                    @if(!empty($profile?->avatar_url))
                        <img src="{{ $profile->avatar_url }}"
                             alt="Avatar"
                             class="w-24 h-24 rounded-full object-cover border">
                    @else
                        <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                            No Image
                        </div>
                    </div>
                @endif
                <div class="flex-1 space-y-2">
                    <h3 class="text-lg font-semibold">
                        {{ $profile->display_name ?? $user->name }}
                    </h3>
                    <p class="text-sm text-gray-600">
                        ログインメール: {{ $user->email }}
                    </p>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-700">
                        <div>
                            <span class="font-semibold">X ユーザーID:</span>
                            <span>{{ $profile?->x_user_id ?? '未設定' }}</span>
                        </div>
                        <div>
                            <span class="font-semibold">X ユーザー名:</span>
                            <span>
                                @if(!empty($profile?->x_username))
                                    @{{ $profile->x_username }}
                                @else
                                    未連携
                                @endif
                            </span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-700 mt-2">
                        <span class="font-semibold">自己紹介:</span><br>
                        {{ $profile?->bio ?? 'まだ自己紹介が登録されていません。' }}
                    </p>
                </div>
            </div>

            {{-- SNSメトリクス（APIで取る想定の場所） --}}
            <div class="bg-white sm:rounded-lg shadow-sm p-6">
                <h3 class="text-md font-semibold mb-4">SNS 指標（API連携で自動更新予定）</h3>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="border rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-1">フォロワー数</p>
                        <p class="text-2xl font-bold">
                            {{ isset($profile?->followers_count) ? number_format($profile->followers_count) : '—' }}
                        </p>
                        <p class="text-[11px] text-gray-400 mt-1">
                            API未設定の場合は「—」と表示されます
                        </p>
                    </div>

                    <div class="border rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-1">ツイート数</p>
                        <p class="text-2xl font-bold">
                            {{ isset($profile?->tweet_count) ? number_format($profile->tweet_count) : '—' }}
                        </p>
                        <p class="text-[11px] text-gray-400 mt-for 1">
                            API連携後に集計されます
                        </p>
                    </div>

                    <div class="border rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-1">フォロー数</p>
                        <p class="text-2xl font-bold">
                            {{ isset($profile?->following_count) ? number_format($profile->following_count) : '—' }}
                        </p>
                    </div>

                    <div class="border rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-1">リスト数</p>
                        <p class="text-2xl font-bold">
                            {{ isset($profile?->listed_count) ? number_format($profile->listed_count) : '—' }}
                        </p>
                    </div>
                </div>

                <p class="text-xs text-gray-500 mt-4">
                    ※ 現時点では API キー未設定のため、フォロワー数などは手動登録された値のみ表示されます。<br>
                    将来的に X API の Bearer Token を設定すると、自動で最新の数値に更新できます。
                </p>
            </div>

            {{-- 銀行情報＆その他（もしあれば） --}}
            @if(isset($profile) && (
                $profile->bank_name
                || $profile->bank_account
                || $profile->location
            ))
                <div class="bg-white sm:rounded-lg shadow-sm p-6">
                    <h3 class="text-md font-semibold mb-4">登録情報</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        @if($profile->location)
                            <div>
                                <span class="font-semibold">所在地:</span>
                                <span>{{ $profile->location }}</span>
                            </div>
                        @endif
                        @if($profile->bank_name)
                            <div>
                                <span class="font-semibold">銀行名:</span>
                                <span>{{ $profile->bank_name }}</span>
                            </div>
                        @endif
                        {{-- 他にも追加したい項目があればここに --}}
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>




