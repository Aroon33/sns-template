<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\CampaignTargetController;
use App\Http\Controllers\XAuthController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\ClientCampaignController;
use App\Http\Controllers\ClientDashboardController;

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

    return match($user->role) {
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
| Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


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

        // ユーザー管理
        Route::get('/users', [UserController::class, 'index'])->name('users.index');

        // 案件管理
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

        // 確定ターゲット一覧
        Route::get('/campaigns/{campaign}/selected-targets', [CampaignTargetController::class, 'index'])
            ->name('campaigns.selected_targets');

        // ターゲットステータス変更
        Route::patch('/campaigns/{campaign}/targets/{target}', [CampaignTargetController::class, 'update'])
            ->name('campaigns.targets.update');

        // ターゲット削除
        Route::delete('/campaigns/{campaign}/targets/{target}', [CampaignTargetController::class, 'destroy'])
            ->name('campaigns.targets.destroy');

        // ★ 全ターゲット一覧（Admin Dashboard 用）
        Route::get('/targets', [CampaignTargetController::class, 'index'])
            ->name('targets.index');
    });


/*
|--------------------------------------------------------------------------
| User Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])
            ->name('dashboard');
    });


/*
|--------------------------------------------------------------------------
| Client Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])
            ->name('dashboard');
 // 案件作成ページ
        Route::get('/campaigns/create', [ClientCampaignController::class, 'create'])
            ->name('campaigns.create');

        // 案件保存
        Route::post('/campaigns', [ClientCampaignController::class, 'store'])
            ->name('campaigns.store');

        // 案件詳細（数字確認ページ）
        Route::get('/campaigns/{campaign}', [ClientCampaignController::class, 'show'])
            ->name('campaigns.show');
    });






