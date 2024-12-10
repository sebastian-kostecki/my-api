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

    public function handle()
    {
        $tasks = $this->notion->databases()->queryPanelalphaTasks($this->panelAlphaNotionDatabaseId);
        $issues = $this->gitLab->getIssuesAssignedToMe();

        $newIssues = $issues->diffKeys($tasks);
        $existingTasks = $tasks->intersectByKeys($issues);

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

        //istniejące issues

        //gdy już jest zakończony to powinno mu zmienić na done
        //trzeba sprawdzać pojedynczo, bo mogą do mnie nie być już przypisane

        //zmienić milestone

        //zmienić priority

    }
}
