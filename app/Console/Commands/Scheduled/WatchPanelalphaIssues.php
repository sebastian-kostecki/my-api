<?php

namespace App\Console\Commands\Scheduled;

use App\Lib\Connections\GitLab;
use App\Lib\Connections\GitLab\Issue;
use App\Lib\Connections\Notion;
use Illuminate\Console\Command;

class WatchPanelalphaIssues extends Command
{
    protected $signature = 'panelalpha:watch-issues';

    protected $description = 'Watch issues in panelalpha.com';

    protected GitLab $gitLab;

    private Notion $notion;

    protected int $panelAlphaProjectId = 2860;

    protected string $panelAlphaNotionDatabaseId = '157980fc8b79807fb01fddf10b492fe8';

    public function __construct()
    {
        parent::__construct();
        $this->gitLab = new GitLab(env('GITLAB_HOST'), env('GITLAB_PERSONAL_TOKEN'));
        $this->gitLab->setProjectId($this->panelAlphaProjectId);

        $this->notion = new Notion;
    }

    public function handle(): void
    {
        $tasks = $this->notion->databases()->queryPanelalphaTasks($this->panelAlphaNotionDatabaseId);
        $issues = $this->gitLab->getIssues();

        $newIssues = $issues->diffKeys($tasks)->filter(function (Issue $issue) {
            return $issue->getAssigneeUsername() === 'sebastian.ko';
        });

        $newIssues->each(function (Issue $issue) {
            $this->notion->pages()->createPanelalphaTaskPage(
                $this->panelAlphaNotionDatabaseId,
                $issue->getId(),
                $issue->getName(),
                $issue->getUrl(),
                $issue->getMilestone(),
                $issue->getPriority(),
                $issue->getLabels(),
                $issue->getDescription()
            );
        });

        $existingTasks = $tasks->intersectByKeys($issues);

        $existingTasks->each(function (array $task) use ($issues) {
            /** @var Issue $issue */
            $issue = $issues->get($task['id']);

            $this->notion->pages()->updatePanelalphaTaskPage(
                $task['page_id'],
                $issue->getStatus(),
                $issue->getMilestone(),
                $issue->getPriority(),
            );
        });

    }
}
