<?php

namespace App\Console\Commands\Scheduled;

use App\Models\Recipient;
use App\Notifications\ReportDailyTasks as ReportDailyTasksNotification;
use FiveamCode\LaravelNotionApi\Entities\Page;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use FiveamCode\LaravelNotionApi\Query\Filters\Filter;
use FiveamCode\LaravelNotionApi\Query\Filters\FilterBag;
use FiveamCode\LaravelNotionApi\Query\Filters\Operators;
use FiveamCode\LaravelNotionApi\Query\Sorting;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class ReportDailyTasksInEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:report-daily-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Report daily tasks in email';

    protected string $tableId;
    protected array $dailyTasks;
    protected array $nextTasks;

    public function __construct()
    {
        parent::__construct();
        $this->tableId = "73c22dd1451d40a48ced9f69a33b195d";
    }

    /**
     * @return void
     * @throws HandlingException
     */
    public function handle(): void
    {
        $this->getDailyTasks();
        $this->getNextTasks();
        if (!empty($this->dailyTasks)) {
            $this->sendNotification();
            $this->clearDailyTasksStatus();
        }
    }

    /**
     * @return void
     * @throws HandlingException
     */
    protected function getDailyTasks(): void
    {
        $todayFilter = Filter::rawFilter("Today", [
            "checkbox" => [Operators::EQUALS => true],
        ]);

        $result = \Notion::database($this->tableId)
            ->filterBy($todayFilter)
            ->sortBy(Sorting::propertySort('Issue', 'ascending'))
            ->query()
            ->asCollection();

        $tasks =  $result->map(function ($item) {
            $taskName = $item->getRawResponse()['properties']['Name']['title'][0]['text']['content'];
            $taskStatus = $item->getRawResponse()['properties']['Status']['select']['name'];
            $issuePageId = $item->getRawResponse()['properties']['Issue']['relation'][0]['id'];
            $issuePage = \Notion::pages()->find($issuePageId);
            $issueName = $issuePage->getRawResponse()['properties']['Title']['title'][0]['text']['content'];
            if ($taskStatus == 'In progress') {
                $taskStatus = '<strong class="in-progress">In progress</strong>';
            } else {
                $taskStatus = '<strong class="done">Done</strong>';
            }

            return '<strong>' . $issueName . '</strong> | ' . $taskName . " " . $taskStatus;
        });
        $this->dailyTasks = $tasks->toArray();
    }

    /**
     * @return void
     * @throws HandlingException
     */
    protected function getNextTasks(): void
    {
        $filterBag = new FilterBag(Operators::AND);
        $filterBag->addFilter(
            Filter::rawFilter("Status", [
                "select" => [Operators::DOES_NOT_EQUAL => 'Done'],
            ])
        );
        $filterBag->addFilter(
            Filter::rawFilter("Status", [
                "select" => [Operators::DOES_NOT_EQUAL => 'Hidden'],
            ])
        );

        $sorting = new Collection();
        $sorting->add(Sorting::propertySort("Priority", "descending"));
        $sorting->add(Sorting::propertySort("Issue", "descending"));
        $sorting->add(Sorting::propertySort("Status", "ascending"));

        $result = \Notion::database($this->tableId)
            ->filterBy($filterBag)
            ->sortBy($sorting)
            ->limit(3)
            ->query()
            ->asCollection();

        $tasks = $result->map(function ($item) {
            $taskName = $item->getRawResponse()['properties']['Name']['title'][0]['text']['content'];
            $issuePageId = $item->getRawResponse()['properties']['Issue']['relation'][0]['id'];
            $issuePage = \Notion::pages()->find($issuePageId);
            $issueName = $issuePage->getRawResponse()['properties']['Title']['title'][0]['text']['content'];
            return "<strong>" . $issueName . "</strong> | " . $taskName;
        });

        $this->nextTasks = $tasks->toArray();
    }

    /**
     * @return void
     */
    protected function sendNotification(): void
    {
        $recipients = Recipient::where('type', 'report')->get();
        Notification::send($recipients, new ReportDailyTasksNotification($this->dailyTasks, $this->nextTasks));
    }

    /**
     * @return void
     */
    protected function clearDailyTasksStatus(): void
    {
        $todayFilter = Filter::rawFilter("Today", [
            "checkbox" => [Operators::EQUALS => true],
        ]);

        $result = \Notion::database($this->tableId)
            ->filterBy($todayFilter)
            ->query()
            ->asCollection();

        $result->each(function ($item) {
            $id = $item->getId();
            $page = new Page();
            $page->setId($id);
            $page->setCheckbox('Today', false);
            \Notion::pages()->update($page);
        });
    }
}
