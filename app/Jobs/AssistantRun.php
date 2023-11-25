<?php

namespace App\Jobs;

use App\Events\SendResponse;
use App\Lib\Apis\OpenAI;
use App\Lib\Assistant\Assistant;
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

    //private Assistant $assistant;

    /**
     * Create a new job instance.
     */
    public function __construct(Thread $thread, array $startedRun)
    {
        $this->thread = $thread;
        $this->startedRun = $startedRun;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $run = OpenAI::factory()->assistant()->run()->retrieve($this->thread->remote_id, $this->startedRun['id']);
        if ($run['status'] !== 'completed') {
            SendResponse::dispatch("");
            $this->reschedule();
            return;
        }

        $response = $this->thread->getLastMessage();
        SendResponse::dispatch($response->text);

//        $this->assistant->setResponse($response->text);
//        $this->assistant->setThread($this->thread->id);
//        $this->assistant->saveResponseToVectorDatabase();
    }

    /**
     * @return void
     */
    public function reschedule(): void
    {
        self::dispatch($this->thread, $this->startedRun)->delay(now()->addSeconds(4));
    }
}
