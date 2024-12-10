<?php

namespace App\Lib\Connections;

use App\Lib\Connections\GitLab\Issue;
use Gitlab\Client;
use Illuminate\Support\Collection;

class GitLab
{
    private Client $client;

    private ?int $projectId = null;

    public function __construct(string $host, string $token, string $authMethod = Client::AUTH_HTTP_TOKEN)
    {
        $this->client = new Client;
        $this->client->setUrl($host);
        $this->client->authenticate($token, $authMethod);
    }

    public function setProjectId(int $projectId): void
    {
        $this->projectId = $projectId;
    }

    public function getIssuesAssignedToMe(): Collection
    {
        if ($this->projectId === null) {
            throw new \Exception('Project ID is not set');
        }

        $result = $this->client->issues()->all($this->projectId, [
            'scope' => 'assigned-to-me',
        ]);

        return collect($result)->mapWithKeys(function ($issue) {
            return [$issue['iid'] => new Issue($issue)];
        });
    }
}
