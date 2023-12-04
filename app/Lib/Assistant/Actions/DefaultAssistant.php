<?php

namespace App\Lib\Assistant\Actions;

use App\Lib\Assistant\Assistant;
use App\Lib\Exceptions\ConnectionException;
use App\Lib\Interfaces\AssistantInterface;
use App\Models\Action;
use App\Models\Message;
use App\Models\Run;
use App\Models\Thread;
use JsonException;

class DefaultAssistant extends AbstractAction implements AssistantInterface
{
    protected Assistant $assistant;
    protected ?Action $action;
    protected ?Thread $thread;
    protected string $response;

    /**
     * @param Assistant $assistant
     * @throws JsonException
     */
    public function __construct(Assistant $assistant)
    {
        $this->assistant = $assistant;
        $this->action = Action::type(static::class)->first();
        $this->thread = $this->action->remoteAssistant->getOrCreateThread($this->assistant->getThreadId(), $this->assistant->getQuery());
    }

    /**
     * @return void
     * @throws JsonException
     * @throws ConnectionException
     */
    public function execute(): void
    {
        Message::createUserMessage($this->thread, $this->assistant->getQuery());
        $run = Run::createRun($this->thread);
        $message = Message::createAssistantMessage($this->thread);

        do {
            sleep(3);
            $run->retrieve();

            if ($run->status === 'failed') {
                $message->markAsFailed($run);
                $this->assistant->setResponse($message->text);
                return;
            }

            if ($run->status === 'in_progress') {
                $message->markAsInProgress();
            }
        } while ($run->status === 'in_progress');

        $message->markAsCompleted();
        $message->saveToVectorDatabase();
        $this->assistant->setResponse($message->text);
    }
}
