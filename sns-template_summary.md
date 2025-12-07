sns-template

1. プロジェクト概要

サイト表示ディレクトリ
/var/www/sns-template/resources/views

プロジェクト名（仮）: sns-template

フレームワーク: Laravel 12.x（PHP 8.2）

用途イメージ:

X（旧Twitter）アカウントを使ってログインするユーザーと

案件を出すクライアント

全体を管理する管理者
からなる SNS連携型の案件マッチング／キャンペーン管理システム。

ドメイン: https://sns-template.com

データベース: MySQL（Laravel からは .env の DB_* 経由で接続）

2. ロール（役割）と認証まわり
2-1. usersテーブルのロール

users.role でロー

general … 一般ユーザー

client … クライアント（案件依頼側）

admin … 管理者

2-2. 代表的なアカウント（テスト用）

Laravel Tinkerで作成した3ユーザー：

管理者:

email: fulladmin@sns-template.com

password: Pass1234

role: admin

クライアント:

email: fullclient@sns-template.com

password: Pass1234

role: client

一般ユーザー:

email: fulluser@sns-template.com

password: Pass1234

role: general

これらは /login からログイン可能。

2-3. Xログイン（OAuth2）

ルート:

Xログイン開始: GET /auth/x/redirect

コールバック: GET /auth/x/callback

コントローラ: app/Http/Controllers/XAuthController.php

動作:

/auth/x/redirect → Xの認証画面へリダイレクト

認証成功後 https://sns-template.com/auth/x/callback に戻る

XAuthController@callback で Socialite::driver('twitter-oauth-2')->user() を使って X ユーザー情報取得

users と user_profiles に

x_user_id

x_username

avatar_url
などを保存

Auth::login($user, true) → /dashboard にリダイレクト

Xログイン用サービス設定:

config/services.php

'twitter-oauth-2' => [
    'client_id'     => env('X_CLIENT_ID'),
    'client_secret' => env('X_CLIENT_SECRET'),
    'redirect'      => env('X_REDIRECT_URI'),
],

'x' => [
    'bearer_token' => env('X_BEARER_TOKEN'), // 将来API同期に使う想定
],


.env に定義済み（Bearer トークンはまだ未使用・将来用）：

X_CLIENT_ID=...
X_CLIENT_SECRET=...
X_REDIRECT_URI=https://sns-template.com/auth/x/callback
X_BEARER_TOKEN=（今は空でOK）

3. ルーティング構成（大まか）

ファイル: routes/web.php

3-1. 共通
Route::get('/', fn() => view('welcome'));

Route::get('/dashboard', function () {
    $user = Auth::user();
    if (! $user) return redirect()->route('login');

    return match($user->role) {
        'admin'  => redirect()->route('admin.dashboard'),
        'client' => redirect()->route('client.dashboard'),
        default  => redirect()->route('user.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

3-2. Xログイン
Route::get('/auth/x/redirect', [XAuthController::class, 'redirect'])
    ->name('auth.x.redirect');
Route::get('/auth/x/callback', [XAuthController::class, 'callback'])
    ->name('auth.x.callback');

3-3. 管理者ルート（/admin）
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])
            ->name('users.show');

        // キャンペーン一覧・編集など
        Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
        // ... create/store/edit/update/destroy
        // ターゲット関連
        Route::get('/campaigns/{campaign}/targets', [CampaignController::class, 'targets'])->name('campaigns.targets');
        Route::post('/campaigns/{campaign}/targets/add', [CampaignController::class, 'addTarget'])->name('campaigns.targets.add');
        Route::post('/campaigns/{campaign}/targets/bulk-add', [CampaignController::class, 'bulkAdd'])->name('campaigns.targets.bulk_add');
        Route::get('/campaigns/{campaign}/selected-targets', [CampaignTargetController::class, 'index'])->name('campaigns.selected_targets');
        Route::patch('/campaigns/{campaign}/targets/{target}', [CampaignTargetController::class, 'update'])->name('campaigns.targets.update');
        Route::delete('/campaigns/{campaign}/targets/{target}', [CampaignTargetController::class, 'destroy'])->name('campaigns.targets.destroy');
        Route::get('/targets', [CampaignTargetController::class, 'index'])->name('targets.index');
    });

3-4. 一般ユーザー（/user）
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

3-5. クライアント（/client）
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
    });

3-6. クライアント会員登録（ゲスト専用）
Route::middleware('guest')->group(function () {
    Route::get('/client/register', [ClientRegisterController::class, 'create'])
        ->name('client.register');

    Route::post('/client/register', [ClientRegisterController::class, 'store'])
        ->name('client.register.store');
});

4. 主なコントローラの役割
4-1. XAuthController

Xログイン開始 (redirect)

コールバック (callback)

Xユーザー情報を取得

users / user_profiles に保存

デフォルトロール: general

ログインして /dashboard へ

4-2. Admin\DashboardController

/admin/dashboard

管理ダッシュボードに表示する集計：

総ユーザー数

総キャンペーン数

総ターゲット数

簡単なカードとリンク表示

4-3. Admin\UserController

index(): /admin/users

User::with('profile')->paginate(20) で一覧取得

resources/views/admin/users/index.blade.php に表示

show(User $user): /admin/users/{user}

ユーザーのプロフィール＋関連ターゲット数などを表示

resources/views/admin/users/mypage.blade.php

4-4. ClientDashboardController

/client/dashboard

自分の案件一覧＋サマリー（案件数、ターゲット数など）

resources/views/client/dashboard.blade.php

4-5. ClientCampaignController

/client/campaigns/create: 案件作成フォーム

/client/campaigns POST: DBに保存

campaignsテーブルに

title

lp_url

daily_budget_max

desired_post_count など

/client/campaigns/{id}: 案件の詳細（作成日・名前・数字）

4-6. UserDashboardController / UserMyPageController

/user/dashboard: ユーザー用ダッシュボード

/user/mypage: ユーザーマイページ（プロフィール・X情報・報酬・銀行情報など）

5. 主なテーブル（ざっくり）
5-1. users

id, name, email, password, role, timestamps

5-2. user_profiles

user_id (FK: users.id)

display_name

x_username

x_user_id

avatar_url

followers_count

tweet_count, following_count, listed_count（追加予定／追加済み）

location

bio

銀行情報（add_bank_fields_to_user_profiles_table による）

bank_name

bank_branch

bank_account_type

bank_account_number

bank_account_holder

reward_total

reward_this_month

5-3. campaigns

id

title（案件名）

lp_url（LPのURL）

daily_budget_max（1日最大広告費）

desired_post_count（希望投稿人数）

posts_count, posters_count, likes_count, retweets_count, views_count

total_ad_cost, today_ad_cost

timestamps

5-4. campaign_targets

id

campaign_id

user_id

status (approved / pending / rejected)

timestamps

5-5. client_profiles

id

user_id (FK: users.id / role: client)

name

type (individual or corp)

company_name

department

contact_email

contact_tel

description（仕事内容・商材など）

6. view / Blade 構成
6-1. 共通

resources/views/welcome.blade.php

Laravel標準のWelcomeをベースに、ヘッダーにナビ追加

ヘッダーで

ログイン前: 「Xでログイン / Log in / Register」

ログイン後: ロールに応じて「ユーザーページ / クライアントページ / 管理者ダッシュボード / ログアウト」

ナビパーツ: resources/views/partials/nav.blade.php

6-2. 管理者

resources/views/admin/dashboard.blade.php

上部: 「管理者ダッシュボード」＋ナビ（ユーザー一覧・案件一覧・クライアント／ユーザー画面など）

サマリーカード（ユーザー数・案件数・ターゲット数）

resources/views/admin/users/index.blade.php

ユーザー一覧テーブル（id, name, email, role, x_username, followers_count, created_at）

各行から「詳細を見る」で /admin/users/{id} へ

resources/views/admin/users/mypage.blade.php

個別ユーザーの詳細表示

基本情報（名前・メール・ロール・登録日時）

Xアカウント情報（x_username, followers_count など）

キャンペーンターゲット情報（targetCount / status ごとの集計）

6-3. ユーザー

resources/views/user/dashboard.blade.php

「マイページ（ユーザー）」として

上部: ログイン中ユーザー名

カード形式で X 情報・followers_count など

銀行口座・所在地なども表示（あれば）

resources/views/user/mypage.blade.php を今後拡張予定（ダッシュボードと分離も可）

6-4. クライアント

resources/views/client/dashboard.blade.php

サマリー（案件数・ターゲット数など）

自分の案件一覧（id, 作成日, 案件名, daily_budget_max, desired_post_count, 詳細リンク）

「＋ 新規案件作成」ボタン

resources/views/client/campaigns/create.blade.php

案件作成フォーム：

作成日（表示のみ）

案件名

LP URL

1日の最大広告費

希望投稿人数

resources/views/client/campaigns/show.blade.php

案件詳細・数字確認（投稿数・いいね数 etc. は将来的にAPIで更新）

resources/views/client/auth/register.blade.php

クライアント会員登録フォーム

ログイン情報（email / password / password_confirmation）

クライアント情報（名前 / 個人・法人 / 会社名 / 部署 / 連絡先 / 仕事内容）

7. X API連携の進捗

現時点：

X OAuth でログイン → OK

users・user_profiles に X のid / username / avatar_url は保存される

マイページ・管理画面で

followers_count などのカラムの値を読み取り表示するUIは用意済み

ただし実際のAPIからフォロワー数などを取って保存する仕組み（サービスクラス・コマンド）は「設計案」までで、まだ実装・検証前

設定済み:

config/services.php に x.bearer_token 用意

.env に X_BEARER_TOKEN 定義（値はまだ入れていない、将来のための placeholder）

8. やりかけ / 今後のTODO候補

 X API v2 を使って followers_count などを自動同期するサービスクラス・コマンド実装

 管理者画面でユーザー検索：
role・followers範囲・キーワード（プロフィール／bio）検索・ソート（followers descなど）

 管理者からのアクション管理ページ：
「このユーザーに対して投稿/いいね/RT依頼」などの設計

 クライアント用：
- 自分の案件のみに絞った表示（campaignsにclient_user_idなど追加）
- 依頼ステータス管理

 ユーザー側：
- マイページUIをダッシュボードから分離（user/mypage.blade.php を本格実装）
- 報酬履歴、案件参加履歴の表示

9. このメモの使い方

新しいチャットにこのまま貼って、

プロジェクト：sns-template（Laravel）

これが現在のルート・コントローラ・ビュー構成

Xログインは動いている

これから「X API同期」「管理画面強化」「ユーザーマイページ強化」を進めたい


🧭 SNS-Template 環境構成まとめ（Markdown版）
📌 サーバー基本情報
項目	内容
OS	Linux (Ubuntu系 / ConoHa VPS 推定)
Webサーバー	nginx または Apache（未確認）
PHP	8.2.29
Laravel	12.39.0
DB	MariaDB (MySQL互換)
DBポート	3306
Laravelプロジェクトパス	/var/www/sns-template
📁 ディレクトリ構造（Laravel標準）
/var/www/sns-template
├── app
│   ├── Http
│   │   ├── Controllers
│   │   ├── Middleware
│   └── Models
├── bootstrap
├── config
├── database
│   ├── migrations
├── public
│   └── index.php
├── resources
│   ├── views
│   │   ├── admin
│   │   ├── client
│   │   ├── user
├── routes
│   └── web.php
├── storage
│   └── logs
│       └── laravel.log
└── vendor

⚙️ .env（DB関連）設定内容

.env | grep DB_ の結果は以下を推定：

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=sns_template
DB_USERNAME=sns_user
DB_PASSWORD=●●●●●●

🗄 データベース（sns_template）構成

確認済みテーブル：

テーブル名	役割
users	ユーザー本体（email/password/role）
user_profiles	ユーザー詳細（X情報・銀行情報）
campaigns	キャンペーン
campaign_targets	キャンペーンのターゲット（ユーザー紐付け）
client_profiles	クライアント情報
api_posts	管理者が作る投稿依頼
api_post_targets	投稿依頼の対象ユーザー
migrations	マイグレーション履歴
🧩 api_post_targets（最新）構造
DESCRIBE api_post_targets;

id             BIGINT UNSIGNED  PK
api_post_id    BIGINT UNSIGNED  FK → api_posts.id
user_id        BIGINT UNSIGNED  FK → users.id
status         VARCHAR(20)      default 'pending'
response_json  LONGTEXT         nullable
created_at     TIMESTAMP        nullable
updated_at     TIMESTAMP        nullable


✔ api_post_id が存在 → Laravel リレーションが正しく動作
✔ 最新のテーブル構造に修正済み

🔐 ユーザー（確認済み）
SELECT id, name, email, role FROM users;


例：

id	name	email	role
17	superadmin	superadmin@sns-template.com
	admin

✔ superadmin アカウントは admin
✔ user_profiles も作成済み

📌 ルーティング構成（admin 部分のみ抜粋）
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/api-posts', [ApiPostController::class, 'index'])
            ->name('api_posts.index');

        Route::get('/api-posts/create', [ApiPostController::class, 'create'])
            ->name('api_posts.create');

        Route::post('/api-posts', [ApiPostController::class, 'store'])
            ->name('api_posts.store');

        Route::get('/api-posts/{apiPost}', [ApiPostController::class, 'show'])
            ->name('api_posts.show');
    });

🎨 視覚ファイル構成（API投稿）
resources/views/admin/api_posts/
├── index.blade.php     # 一覧
├── create.blade.php    # 作成（最新完全版）
└── show.blade.php      # 詳細ページ

📦 API投稿のコントローラ
app/Http/Controllers/Admin/ApiPostController.php

提供する機能

index()：一覧表示

create()：作成フォーム

store()：投稿保存 → 対象ユーザー登録 → 送信ジョブ投入

show()：投稿詳細表示

📮 X API 送信関連（サービス／ジョブ）
サービス
app/Services/XApiService.php

キュージョブ
app/Jobs/SendApiPostJob.php

送信設定（.env）
X_BEARER_TOKEN=xxxxx
X_API_BASE=https://api.x.com/2

🧭 管理者周りの補足
AdminMiddleware
app/Http/Middleware/AdminMiddleware.php

superadmin の挙動

role=admin

user_profiles あり

すべての /admin/ ページがアクセス可

💡 技術的ポイントまとめ
部分	内容
Laravel	12.x Jetstream/Breeze 構成
Xログイン	Socialite（twitter-oauth-2）使用
API投稿	管理者のみできる
リレーション	ApiPost ⇄ ApiPostTarget（1:N）
マイグレーション	一部重複を整理済み
DB	MariaDB 3306
🧩 確認や追加を行いたい項目は？

Webサーバー（nginx / apache）の設定も知りたい

権限（chmod / chown）の整備をしたい

GitHub と同期する構成まとめ

X API の投稿処理を本番仕様へ

ユーザー側の投稿依頼ページも作りたい

など、必要な項目があれば続けて追加でまとめます。

1992513733512380416PostMatch71 wants to access your X account.
Match Post
@MatchPostd1ok
This app was created to use the X API.
Things this App can view...
All the posts you can view, including posts from protected accounts.
Your email address.
Any account you can view, including protected accounts.

https://sns-template.com/user/dashboard

https://sns-template.com/user/mypage

https://sns-template.com/profile


Client ID

VWR5SktRa2ZXeVc4Z1dyMEZrUnk6MTpjaQ

copy-light
Copy
Client Secret

WiKSipaWYyi9WwPieDwmN41azvjLGQXoRL_yGPK8lDTO-MiYx0

copy-light
Copy


WiKSipaWYyi9WwPieDwmN41azvjLGQXoRL_yGPK8lDTO-MiYx0