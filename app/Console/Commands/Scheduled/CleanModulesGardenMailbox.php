<?php

namespace App\Console\Commands\Scheduled;

use App\Lib\Connections\Mailbox\ModulesGardenCom;
use Illuminate\Console\Command;

class CleanModulesGardenMailbox extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:clean-panel-alpha-mailbox';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean sebastian.kostecki@panealpha.com from emails';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $mailbox = new ModulesGardenCom();
        $mailbox->clean();
    }
}
