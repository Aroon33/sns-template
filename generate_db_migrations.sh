#!/bin/bash

echo "=== Generating DB Migration Files for X-Action System ==="

# 1. Add client_user_id to campaigns
echo "Creating migration: add_client_user_id_to_campaigns_table"
php artisan make:migration add_client_user_id_to_campaigns_table --table=campaigns

# 2. Create campaign_posts (投稿テンプレ)
echo "Creating migration: create_campaign_posts_table"
php artisan make:migration create_campaign_posts_table --create=campaign_posts

# 3. Create campaign_post_runs (投稿実行ログ)
echo "Creating migration: create_campaign_post_runs_table"
php artisan make:migration create_campaign_post_runs_table --create=campaign_post_runs

# 4. Create client_hashtags (クライアント固有ハッシュタグ)
echo "Creating migration: create_client_hashtags_table"
php artisan make:migration create_client_hashtags_table --create=client_hashtags

echo "=== DONE: Migration files generated ==="
echo "次のステップ：database/migrations/*.php を編集してスキーマを貼り付けてください。"
