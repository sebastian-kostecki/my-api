<?php

namespace App\Console\Commands;

use App\Lib\Connections\Notion;
use App\Mail\ReportDailyTasks;
use Brd6\NotionSdkPhp\Exception\ApiResponseException;
use Brd6\NotionSdkPhp\Exception\HttpResponseException;
use Brd6\NotionSdkPhp\Exception\InvalidPaginationResponseException;
use Brd6\NotionSdkPhp\Exception\InvalidResourceException;
use Brd6\NotionSdkPhp\Exception\InvalidResourceTypeException;
use Brd6\NotionSdkPhp\Exception\RequestTimeoutException;
use Brd6\NotionSdkPhp\Exception\UnsupportedPaginationResponseTypeException;
use Http\Client\Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

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

    protected Notion $notion;
    protected string $tableId;

    public function __construct(Notion $notion)
    {
        parent::__construct();
        $this->notion = $notion;
        $this->tableId = "73c22dd1451d40a48ced9f69a33b195d";
    }


    /**
     * Execute the console command.
     *
     * @return void
     * @throws ApiResponseException
     * @throws Exception
     * @throws HttpResponseException
     * @throws InvalidPaginationResponseException
     * @throws RequestTimeoutException
     * @throws UnsupportedPaginationResponseTypeException
     */
    public function handle()
    {
        $dailyTasks = $this->getDailyTasks();
        if (!empty($dailyTasks)) {
            $nextTasks = $this->getNextTasks();
            foreach ([
                         'sebastian.kostecki@panelalpha.com',
                         'konrad.keck@panelalpha.com',
                         'pawel.koziol@panelalpha.com'
                     ] as $recipient) {
                Mail::to($recipient)->send(new ReportDailyTasks($dailyTasks, $nextTasks));
            }
            $this->clearDailyTasksStatus();
        }
    }


    /**
     * @return array
     * @throws ApiResponseException
     * @throws Exception
     * @throws HttpResponseException
     * @throws InvalidPaginationResponseException
     * @throws InvalidResourceException
     * @throws InvalidResourceTypeException
     * @throws RequestTimeoutException
     * @throws UnsupportedPaginationResponseTypeException
     */
    protected function getDailyTasks(): array
    {
        $filter = $this->getDailyTasksFilter();
        $sorts = $this->getDailyTasksSort();
        $response = $this->notion->getDatabaseItemsByFilter($this->tableId, $filter, $sorts);
        $dailyTasks = $response->getResults();
        return array_map(function ($item) {
            $taskName = $item->getRawData()['properties']['Name']['title'][0]['text']['content'];
            $taskStatus = $item->getRawData()['properties']['Status']['select']['name'];
            $issuePageId = $item->getRawData()['properties']['Issue']['relation'][0]['id'];
            $issuePage = $this->notion->getPage($issuePageId);
            $issueName = $issuePage->getRawData()['properties']['Title']['title'][0]['text']['content'];
            if ($taskStatus == 'In progress') {
                $taskStatus = '<strong class="in-progress">In progress</strong>';
            } else {
                $taskStatus = '<strong class="done">Done</strong>';
            }

            return '<strong>' . $issueName . '</strong> | ' . $taskName . " " . $taskStatus;
        }, $dailyTasks);
    }

    /**
     * @return array[]
     */
    protected function getDailyTasksFilter(): array
    {
        return [
            'and' => [
                [
                    'property' => 'Today',
                    'checkbox' => [
                        'equals' => true,
                    ],
                ],
            ]
        ];
    }

    protected function getDailyTasksSort(): array
    {
        return [
            [
                'property' => 'Issue',
                'direction' => 'descending'
            ],
        ];
    }

    /**
     * @return array
     * @throws ApiResponseException
     * @throws Exception
     * @throws HttpResponseException
     * @throws InvalidPaginationResponseException
     * @throws RequestTimeoutException
     * @throws UnsupportedPaginationResponseTypeException
     * @throws InvalidResourceException
     * @throws InvalidResourceTypeException
     */
    protected function getNextTasks(): array
    {
        $filter = $this->getNextTasksFilter();
        $sorts = $this->getNextTasksSort();
        $response = $this->notion->getDatabaseItemsByFilter($this->tableId, $filter, $sorts);
        $nextTasks = $response->getResults();
        $nextTasks = array_map(function ($item) {
            $taskName = $item->getRawData()['properties']['Name']['title'][0]['text']['content'];
            $issuePageId = $item->getRawData()['properties']['Issue']['relation'][0]['id'];
            $issuePage = $this->notion->getPage($issuePageId);
            $issueName = $issuePage->getRawData()['properties']['Title']['title'][0]['text']['content'];
            return "<strong>" . $issueName . "</strong> | " . $taskName;
        }, $nextTasks);
        return array_slice($nextTasks, 0, 3);
    }

    /**
     * @return array[]
     */
    protected function getNextTasksFilter(): array
    {
        return [
            'and' => [
                [
                    'property' => 'Status',
                    'select' => [
                        'does_not_equal' => 'Done'
                    ]
                ]
            ]
        ];
    }

    /**
     * @return array[]
     */
    protected function getNextTasksSort(): array
    {
        return [
            [
                'property' => 'Status',
                'direction' => 'ascending'
            ],
            [
                'property' => 'Priority',
                'direction' => 'descending'
            ],
            [
                'property' => 'Issue',
                'direction' => 'descending'
            ],
        ];
    }

    /**
     * @return void
     */
    protected function clearDailyTasksStatus(): void
    {
        $item = [
            "properties" => [
                "Today" => [
                    "type" => "checkbox",
                    "checkbox" => false
                ]
            ]
        ];
        $this->notion->updateDatabaseItems($this->tableId, $item);
    }
}
