<?php

namespace App\Lib\Assistant\Actions;

use App\Lib\Assistant\Assistant;
use App\Lib\Exceptions\ConnectionException;
use App\Lib\Interfaces\AssistantInterface;
use App\Models\Action;
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
        $this->thread = $this->action->assistant->getOrCreateThread($this->assistant->getThreadId());
        if (!$this->assistant->getThreadId()) {
            $this->thread->createDescription($this->assistant->getQuery());
        }
    }

    /**
     * @return void
     * @throws ConnectionException
     * @throws JsonException
     */
    public function execute(): void
    {
        $this->thread->createMessage($this->assistant->getQuery());
        $this->action->assistant->run($this->thread->remote_id);
        $response = $this->thread->getLastMessage();
        $this->assistant->setResponse($response->text);
        $this->assistant->setThread($this->thread->id);
        $this->assistant->saveResponseToVectorDatabase();
    }
}