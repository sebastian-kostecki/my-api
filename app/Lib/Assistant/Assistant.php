<?php

namespace App\Lib\Assistant;

use App\Lib\Apis\OpenAI;
use App\Lib\Assistant\Actions\Query;
use App\Lib\Connections\Qdrant;
use App\Models\Action;

class Assistant
{
    protected const DATABASE_NAME = 'myapi';

    public OpenAI $api;
    public Qdrant $vectorDatabase;

    private string $query;
    private int $threadId;
    private ?Action $action;

    private string $response;

    public function __construct()
    {
        $this->api = new OpenAI();
        $this->vectorDatabase = new Qdrant(self::DATABASE_NAME);
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        if (!$this->action) {
            $action = new Query($this);
        } else {
            $action = $this->action->factory($this);
        }
        $action->execute();
    }

    /**
     * @param string $query
     * @return void
     */
    public function setQuery(string $query): void
    {
        $this->query = $query;
    }

    /**
     * @param int|null $threadId
     * @return void
     */
    public function setThread(?int $threadId): void
    {
        $this->threadId = $threadId ?? null;
    }

    /**
     * @param string|null $action
     * @return void
     */
    public function setAction(?string $action): void
    {
        $this->action = Action::type($action)->first();
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @return int|null
     */
    public function getThreadId(): ?int
    {
        return $this->threadId ?? null;
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }

    /**
     * @param string $response
     * @return void
     */
    public function setResponse(string $response): void
    {
        $this->response = $response;
    }
}
