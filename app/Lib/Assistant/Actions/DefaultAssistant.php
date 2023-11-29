<?php

namespace App\Lib\Assistant\Actions;

use App\Jobs\AssistantRun;
use App\Lib\Apis\OpenAI;
use App\Lib\Assistant\Assistant;
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
     */
    public function execute(): void
    {
        Message::createUserMessage($this->thread, $this->assistant->getQuery());
        $startedRun = Run::createRun($this->thread);
        $assistantMessage = Message::createAssistantMessage($this->thread);
        AssistantRun::dispatch($assistantMessage, $startedRun);

        $this->assistant->setResponse('Myślę');
    }
}
