<?php

namespace App\Console\Commands\Scheduled;

use App\Lib\Connections\Mailbox\PanelalphaCom;
use Illuminate\Console\Command;

class ManagePanelAlphaMailbox extends Command
{
    protected $signature = 'email:manage-panelalpha-mailbox';

    protected $description = 'Manage sebastian.kostecki@panelalpha.com mailbox';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $mailbox = new PanelalphaCom;
        $mailbox->moveReportsEmailToFolder();
    }
}
