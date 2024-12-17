<?php

namespace App\Console\Commands\Scheduled;

use App\Lib\Connections\Notion;
use App\Models\Recipient;
use App\Notifications\ReportDailyTasks as ReportDailyTasksNotification;
use Exception;
use FiveamCode\LaravelNotionApi\Exceptions\HandlingException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ReportDailyTasksInEmail extends Command
{
    protected $signature = 'email:report-daily-tasks';

    protected $description = 'Report daily tasks in email';

    protected string $tableId;

    private Notion $notion;

    public function __construct()
    {
        parent::__construct();
        $this->tableId = '157980fc8b79807fb01fddf10b492fe8';

        $this->notion = new Notion;
    }

    /**
     * @throws HandlingException
     */
    public function handle(): void
    {
        Log::channel('tasks')->info('Task <<' . class_basename(__CLASS__) . '>> is running.');

        try {
            $todayTasks = $this->notion->databases()->queryPanelalphaTodayTasks($this->tableId)->all();
            $nextTasks = $this->notion->databases()->queryPanelalphaNextTasks($this->tableId)->take(5)->all();

            if (!empty($todayTasks)) {
                $recipients = Recipient::where('type', 'report')->get();
                Notification::send($recipients, new ReportDailyTasksNotification($todayTasks, $nextTasks));
                $this->notion->databases()->clearDailyTasksStatus($this->tableId);
            }

            Log::channel('tasks')->info('Task <<' . class_basename(__CLASS__) . '>> has been done.');
        } catch (Exception $exception) {
            Log::channel('tasks')->error('Task <<' . class_basename(__CLASS__) . '>> failed with :', [
                'exception' => $exception,
            ]);
        }
    }
}
