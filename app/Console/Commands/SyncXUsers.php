<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\XUserSyncService;

class SyncXUsers extends Command
{
    protected $signature = 'x:sync-users';
    protected $description = 'XのAPIからユーザー情報・メトリクスを同期する';

    public function handle(XUserSyncService $service): int
    {
        $this->info('Start syncing X users...');
        $service->syncAllUsers();
        $this->info('Done.');
        return self::SUCCESS;
    }
}

