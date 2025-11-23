{{-- resources/views/user/dashboard.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ユーザーダッシュボード
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(isset($user))
                        {{ $user->name }} さん、ようこそ！
                    @else
                        ログインユーザー情報が取得できませんでした。
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



