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
| Dashboard（ロール別）
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
| X Login
|--------------------------------------------------------------------------
*/
Route::get('/auth/x/redirect', [XAuthController::class, 'redirect'])->name('auth.x.redirect');
Route::get('/auth/x/callback', [XAuthController::class, 'callback'])->name('auth.x.callback');


/*
|--------------------------------------------------------------------------
| Profile（共通）
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
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

        // ------------------------------
        // ダッシュボード
        // ------------------------------
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');


        // ------------------------------
        // ユーザー管理
        // ------------------------------
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::post('/users/{user}/actions', [UserController::class, 'storeAction'])
            ->name('users.actions.store');


        // ------------------------------
        // API投稿
        // ------------------------------

        // ★ API投稿一覧（最重要！）
        Route::get('/api-posts', [ApiPostController::class, 'index'])
            ->name('api_posts.index');

        // 作成ページ
        Route::get('/api-posts/create', [ApiPostController::class, 'create'])
            ->name('api_posts.create');

        // 登録
        Route::post('/api-posts', [ApiPostController::class, 'store'])
            ->name('api_posts.store');

        // 詳細
        Route::get('/api-posts/{apiPost}', [ApiPostController::class, 'show'])
            ->name('api_posts.show');


        // ------------------------------
        // 案件管理（キャンペーン）
        // ------------------------------
        Route::get('/campaigns', [CampaignController::class, 'index'])
            ->name('campaigns.index');

        Route::get('/campaigns/create', [CampaignController::class, 'create'])
            ->name('campaigns.create');

        Route::post('/campaigns', [CampaignController::class, 'store'])
            ->name('campaigns.store');

        Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])
            ->name('campaigns.edit');

        Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])
            ->name('campaigns.update');

        Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])
            ->name('campaigns.destroy');

        Route::get('/campaigns/{campaign}/targets', [CampaignController::class, 'targets'])
            ->name('campaigns.targets');

        Route::post('/campaigns/{campaign}/targets/add', [CampaignController::class, 'addTarget'])
            ->name('campaigns.targets.add');

        Route::post('/campaigns/{campaign}/targets/bulk-add', [CampaignController::class, 'bulkAdd'])
            ->name('campaigns.targets.bulk_add');

        Route::get('/campaigns/{campaign}/selected-targets', [CampaignTargetController::class, 'index'])
            ->name('campaigns.selected_targets');

        Route::patch('/campaigns/{campaign}/targets/{target}', [CampaignTargetController::class, 'update'])
            ->name('campaigns.targets.update');

        Route::delete('/campaigns/{campaign}/targets/{target}', [CampaignTargetController::class, 'destroy'])
            ->name('campaigns.targets.destroy');

        // 全ターゲット一覧
        Route::get('/targets', [CampaignTargetController::class, 'all'])
            ->name('targets.index');
    });


/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/dashboard', [UserDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/mypage', [UserMyPageController::class, 'show'])
            ->name('mypage');

        Route::post('/mypage/bank', [UserMyPageController::class, 'updateBankInfo'])
            ->name('mypage.bank.update');
    });


/*
|--------------------------------------------------------------------------
| Client Routes
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

        Route::get('/profile', [ClientProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::patch('/profile', [ClientProfileController::class, 'update'])
            ->name('profile.update');
    });


/*
|--------------------------------------------------------------------------
| Client Register
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/client/register', [ClientRegisterController::class, 'create'])
        ->name('client.register');

    Route::post('/client/register', [ClientRegisterController::class, 'store'])
        ->name('client.register.store');
});
