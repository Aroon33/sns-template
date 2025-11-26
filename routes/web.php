<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\CampaignTargetController;
use App\Http\Controllers\Admin\ApiPostController;
use App\Http\Controllers\XAuthController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\ClientCampaignController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\UserMyPageController;
use App\Http\Controllers\ClientRegisterController;
use App\Http\Controllers\ClientProfileController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard 集約ルート（Role によって振り分け）
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = Auth::user();

    if (! $user) {
        return redirect()->route('login');
    }

    return match ($user->role) {
        'admin'  => redirect()->route('admin.dashboard'),
        'client' => redirect()->route('client.dashboard'),
        default  => redirect()->route('user.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| X Login Routes
|--------------------------------------------------------------------------
*/

Route::get('/auth/x/redirect', [XAuthController::class, 'redirect'])
    ->name('auth.x.redirect');

Route::get('/auth/x/callback', [XAuthController::class, 'callback'])
    ->name('auth.x.callback');


/*
|--------------------------------------------------------------------------
| Profile Routes（共通）
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ダッシュボード
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        /*
        |------------------------------
        | ユーザー管理
        |------------------------------
        */

        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        // ユーザー詳細（管理者用マイページ）
        Route::get('/users/{user}', [UserController::class, 'show'])
            ->name('users.show');

        // 管理者 → ユーザーへのアクション登録
        Route::post('/users/{user}/actions', [UserController::class, 'storeAction'])
            ->name('users.actions.store');

        /*
        |------------------------------
        | API投稿（X API 連携用の土台）
        |------------------------------
        */

        // API投稿作成ページ
        Route::get('/api-posts/create', [ApiPostController::class, 'create'])
            ->name('api_posts.create');

        // API投稿登録（将来ここでX APIキュー投入）
        Route::post('/api-posts', [ApiPostController::class, 'store'])
            ->name('api_posts.store');

        /*
        |------------------------------
        | 案件管理（キャンペーン）
        |------------------------------
        */

        Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
        Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
        Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
        Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
        Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
        Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');

        // 対象ユーザー候補一覧
        Route::get('/campaigns/{campaign}/targets', [CampaignController::class, 'targets'])
            ->name('campaigns.targets');

        // 対象へ追加
        Route::post('/campaigns/{campaign}/targets/add', [CampaignController::class, 'addTarget'])
            ->name('campaigns.targets.add');

        // 一括追加
        Route::post('/campaigns/{campaign}/targets/bulk-add', [CampaignController::class, 'bulkAdd'])
            ->name('campaigns.targets.bulk_add');

        // 確定ターゲット一覧（特定キャンペーン）
        Route::get('/campaigns/{campaign}/selected-targets', [CampaignTargetController::class, 'index'])
            ->name('campaigns.selected_targets');

        // ターゲットステータス変更
        Route::patch('/campaigns/{campaign}/targets/{target}', [CampaignTargetController::class, 'update'])
            ->name('campaigns.targets.update');

        // ターゲット削除
        Route::delete('/campaigns/{campaign}/targets/{target}', [CampaignTargetController::class, 'destroy'])
            ->name('campaigns.targets.destroy');

        // 全ターゲット一覧（キャンペーン横断）
        Route::get('/targets', [CampaignTargetController::class, 'all'])
            ->name('targets.index');
    });


/*
|--------------------------------------------------------------------------
| User Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])
            ->name('dashboard');

        // マイページ
        Route::get('/mypage', [UserMyPageController::class, 'show'])
            ->name('mypage');

        // 銀行情報更新
        Route::post('/mypage/bank', [UserMyPageController::class, 'updateBankInfo'])
            ->name('mypage.bank.update');
    });


/*
|--------------------------------------------------------------------------
| Client Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/campaigns/create', [ClientCampaignController::class, 'create'])
            ->name('campaigns.create');
        Route::post('/campaigns', [ClientCampaignController::class, 'store'])
            ->name('campaigns.store');
        Route::get('/campaigns/{campaign}', [ClientCampaignController::class, 'show'])
            ->name('campaigns.show');

        // クライアントプロフィール
        Route::get('/profile', [ClientProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::patch('/profile', [ClientProfileController::class, 'update'])
            ->name('profile.update');
    });


/*
|--------------------------------------------------------------------------
| Client Register Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/client/register', [ClientRegisterController::class, 'create'])
        ->name('client.register');

    Route::post('/client/register', [ClientRegisterController::class, 'store'])
        ->name('client.register.store');
});
