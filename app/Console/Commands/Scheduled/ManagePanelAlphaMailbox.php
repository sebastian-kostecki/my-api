<?php

namespace App\Console\Commands\Scheduled;

use App\Lib\Connections\Mailbox\PanelalphaCom;
use Illuminate\Console\Command;

class ManagePanelAlphaMailbox extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:manage-panelalpha-mailbox';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage sebastian.kostecki@panelalpha.com mailbox';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mailbox = new PanelalphaCom;
        $mailbox->moveReportsEmailToFolder();
    }
}
