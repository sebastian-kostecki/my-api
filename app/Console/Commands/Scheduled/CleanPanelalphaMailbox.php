<?php

namespace App\Console\Commands\Scheduled;

use App\Lib\Connections\Mailbox\PanelalphaCom;
use Illuminate\Console\Command;

class CleanPanelalphaMailbox extends Command
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
        $mailbox = new PanelalphaCom;
        $mailbox->clean();
    }
}
