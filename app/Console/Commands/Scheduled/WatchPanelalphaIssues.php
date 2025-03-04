<?php

namespace App\Console\Commands\Scheduled;

use App\Lib\Connections\GitLab;
use App\Lib\Connections\GitLab\Issue;
use App\Lib\Connections\Notion;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

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
        Log::channel('tasks')->info('Task <<'.class_basename(__CLASS__).'>> is running.');

        try {
            $tasks = $this->notion->databases()->queryPanelalphaTasks($this->panelAlphaNotionDatabaseId);

            $this->updateTasks($tasks);
            $this->addNewTasks($tasks);

            Log::channel('tasks')->info('Task <<'.class_basename(__CLASS__).'>> has been done.');
        } catch (Exception $e) {
            Log::channel('tasks')->error('Task <<'.class_basename(__CLASS__).'>> failed with :', [
                'exception' => $e,
            ]);
        }
    }

    /**
     * @throws Exception
     */
    private function updateTasks(Collection $tasks): void
    {
        $issues = $this->gitLab->getIssues(ids: $tasks->keys()->toArray());
        $issues->each(function (Issue $issue) use ($tasks) {
            $targetTask = $tasks[$issue->getId()];

            $this->notion->pages()->updatePanelalphaTaskPage(
                $targetTask['page_id'],
                $issue->getStatus(),
                $issue->getMilestone(),
                $issue->getPriority(),
                $issue->getEndDate($targetTask['end_date'])
            );
        });
    }

    /**
     * @throws Exception
     */
    private function addNewTasks(Collection $tasks): void
    {
        $issuesAssignedToMe = $this->gitLab->getIssues('assigned-to-me', 'opened');
        $issuesAssignedToMe->each(function (Issue $issue) use ($tasks) {
            if (empty($tasks[$issue->getId()])) {
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
            }
        });
    }
}
