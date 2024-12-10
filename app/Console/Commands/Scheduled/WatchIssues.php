<?php

namespace App\Console\Commands\Scheduled;

use FiveamCode\LaravelNotionApi\Entities\Blocks\HeadingOne;
use FiveamCode\LaravelNotionApi\Entities\Blocks\Paragraph;
use FiveamCode\LaravelNotionApi\Entities\Page;
use Gitlab\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class WatchIssues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notion:watch-issues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Watch issues in GitLab';

    protected Client $gitLabClient;

    protected int $panelAlphaProjectId = 2860;

    protected string $panelAlphaNotionDatabaseId = 'e5f489afb035406bbcfbd57d9533147a';

    public function __construct()
    {
        parent::__construct();
        $this->gitLabClient = new Client;
        $this->gitLabClient->setUrl(env('GITLAB_HOST'));
        $this->gitLabClient->authenticate(env('GITLAB_PERSONAL_TOKEN'), Client::AUTH_HTTP_TOKEN);
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $gitlabIssues = $this->getGitLabIssuesAssignedToMe();
        $notionIssues = $this->getNotionIssues();

        $updateIssues = $this->getIssuesToUpdate($gitlabIssues, $notionIssues);
        $createIssues = $this->getIssuesToCreate($gitlabIssues, $notionIssues);

        $this->update($updateIssues);
        $this->create($createIssues);
    }

    protected function getGitLabIssuesAssignedToMe(): Collection
    {
        $gitlabIssues = $this->gitLabClient->issues()->all($this->panelAlphaProjectId, [
            'scope' => 'assigned-to-me',
        ]);
        $gitlabIssues = array_map(function ($item) {
            return [
                'title' => $item['title'],
                'issue_id' => $item['iid'],
                'state' => $item['state'],
                'url' => $item['web_url'],
                'description' => $item['description'],
            ];
        }, $gitlabIssues);

        return collect($gitlabIssues);
    }

    protected function getNotionIssues(): Collection
    {
        $notionIssues = \Notion::database($this->panelAlphaNotionDatabaseId)
            ->query()
            ->asCollection();

        return $notionIssues->map(function ($item) {
            return [
                'page_id' => $item->getId(),
                'title' => $item->getRawResponse()['properties']['Title']['title'][0]['text']['content'],
                'state' => $item->getRawResponse()['properties']['State']['select']['name'] ?? '',
                'url' => $item->getRawResponse()['properties']['URL']['url'] ?? '',
            ];
        });
    }

    protected function getIssuesToUpdate($gitlabIssues, $notionIssues): Collection
    {
        return $gitlabIssues->map(function ($item) use ($notionIssues) {
            $matchingItem = $notionIssues->where('url', $item['url'])->first();
            if ($matchingItem) {
                $item['page_id'] = $matchingItem['page_id'];
            }

            return $item;
        })->filter(function ($item) {
            return isset($item['page_id']);
        });
    }

    protected function getIssuesToCreate($gitlabIssues, $notionIssues): Collection
    {
        return $gitlabIssues->filter(function ($issue) use ($notionIssues) {
            return ! $notionIssues->contains('url', $issue['url']);
        });
    }

    protected function update($updatedIssues): void
    {
        $updatedIssues->each(function ($issue) {
            $page = new Page;
            $page->setId($issue['page_id']);
            $page->setUrl('URL', $issue['url']);
            $page->setSelect('State', $issue['state']);
            $page->setTitle('Title', "#{$issue['issue_id']} {$issue['title']}");
            \Notion::pages()->update($page);
        });
    }

    protected function create($createdIssues): void
    {
        $createdIssues->each(function ($issue) {
            $page = new Page;
            $page->setTitle('Title', "#{$issue['issue_id']} {$issue['title']}");
            $page->setUrl('URL', $issue['url']);
            $page->setSelect('State', $issue['state']);
            if ($issue['state'] === 'closed') {
                $page->setSelect('Status', 'Done');
            } else {
                $page->setSelect('Status', 'Not Started');
            }
            $page = \Notion::pages()->createInDatabase($this->panelAlphaNotionDatabaseId, $page);
            $heading = HeadingOne::create('Description');
            $headingBlock = \Notion::block($page->getId())->append($heading);

            $newParagraph = Paragraph::create($issue['description']);
            \Notion::block($headingBlock->getId())->append($newParagraph);
        });
    }
}
