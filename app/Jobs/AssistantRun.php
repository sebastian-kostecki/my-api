<?php

namespace App\Jobs;

use App\Events\SendResponse;
use App\Lib\Apis\OpenAI;
use App\Models\Message;
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

    private Run $run;
    private Message $message;

    /**
     * Create a new job instance.
     */
    public function __construct(Message $message, Run $run)
    {
        $this->message = $message;
        $this->run = $run;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $run = $this->run->retrieve();

        if ($run->status === 'failed') {
            $this->message->markAsFailed($run);
            SendResponse::dispatch($this->message);
            return;
        }

        if ($run->status === 'in_progress') {
            $this->message->markAsInProgress();
            SendResponse::dispatch($this->message);
            $this->reschedule();
            return;
        }

        $this->message->markAsCompleted();
        SendResponse::dispatch($this->message);
    }

    /**
     * @return void
     */
    public function reschedule(): void
    {
        self::dispatch($this->message, $this->run)->delay(now()->addSeconds(4));
    }
}
