<?php

namespace App\Console\Commands\Scheduled;

use App\Lib\Connections\Mailbox\ModulesGardenCom;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanModulesGardenMailbox extends Command
{
    protected $signature = 'email:clean-modulesgarden-mailbox';
    protected $description = 'Clean sebastian.kostecki@panealpha.com from emails';

    public function __construct(public ModulesGardenCom $mailbox)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        Log::channel('tasks')->info('Task <<' . class_basename(__CLASS__) . '>> is running.');
        try {
            $this->mailbox->clean();

            Log::channel('tasks')->info('Task <<' . class_basename(__CLASS__) . '>> has been done.');
        } catch (Exception $exception) {
            Log::channel('tasks')->error('Task <<' . class_basename(__CLASS__) . '>> failed with :', [
                'exception' => $exception,
            ]);
        }
    }
}
