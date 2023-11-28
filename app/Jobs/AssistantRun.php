<?php

namespace App\Jobs;

use App\Events\SendResponse;
use App\Lib\Apis\OpenAI;
use App\Models\Run;
use App\Models\Thread;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AssistantRun implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Thread $thread;
    private array $startedRun;

    private Run $run;

    /**
     * Create a new job instance.
     */
    public function __construct(Run $run)
    {
        $this->run = $run;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $run = OpenAI::factory()->assistant()->run()->retrieve($this->thread->remote_id, $this->startedRun['id']);

        if ($run['last_error']){
            SendResponse::dispatch('error', $run['last_error'],);
            return;
        }

        if ($run['status'] !== 'completed') {
            SendResponse::dispatch('in_progress', "");
            $this->reschedule();
            return;
        }

        $response = $this->thread->getLastMessage();
        SendResponse::dispatch('completed', $response->text);
    }

    /**
     * @return void
     */
    public function reschedule(): void
    {
        self::dispatch($this->thread, $this->startedRun)->delay(now()->addSeconds(4));
    }
}
