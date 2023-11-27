<?php

namespace App\Lib\Assistant\Actions;

use App\Jobs\AssistantRun;
use App\Lib\Apis\OpenAI;
use App\Lib\Assistant\Assistant;
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
     */
    public function execute(): void
    {
        $this->thread->createMessage($this->assistant->getQuery());
        $startedRun = OpenAI::factory()->assistant()->run()->create($this->thread->remote_id, $this->thread->assistant->remote_id);
        AssistantRun::dispatch($this->thread, $startedRun);
        $this->assistant->setResponse('Myślę');
    }
}
