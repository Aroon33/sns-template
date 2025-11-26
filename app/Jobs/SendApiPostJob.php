<?php

namespace App\Jobs;

use App\Models\ApiPost;
use App\Services\XApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendApiPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ApiPost $post;

    public function __construct(ApiPost $post)
    {
        $this->post = $post;
    }

    public function handle(XApiService $xApiService): void
    {
        $post = $this->post->fresh(['targets.user.profile']);

        if (! $post) {
            return;
        }

        foreach ($post->targets as $target) {
            // すでに処理済みならスキップ
            if (! in_array($target->status, ['pending', null], true)) {
                continue;
            }

            try {
                $result = $xApiService->postForTarget($post, $target);

                if ($result['success']) {
                    $target->status        = 'sent';
                    $target->response_json = $result['body'];
                } else {
                    $target->status        = 'failed';
                    $target->response_json = $result['body'];
                }

                $target->save();
            } catch (\Throwable $e) {
                Log::error('SendApiPostJob error', [
                    'post_id'   => $post->id,
                    'target_id' => $target->id,
                    'error'     => $e->getMessage(),
                ]);

                $target->status = 'failed';
                $target->save();
            }
        }
    }
}

