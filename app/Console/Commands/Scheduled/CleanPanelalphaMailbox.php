<?php

namespace App\Console\Commands\Scheduled;

use App\Lib\Connections\Mailbox\PanelalphaCom;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanPanelalphaMailbox extends Command
{
    protected $signature = 'email:clean-panel-alpha-mailbox';

    protected $description = 'Clean sebastian.kostecki@panealpha.com from emails';

    public function __construct(public PanelalphaCom $mailbox)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        Log::channel('tasks')->info('Task <<'.class_basename(__CLASS__).'>> is running.');

        $actions = [
            [
                'email' => 'expiry@letsencrypt.org',
                'date' => null,
            ],
            [
                'email' => 'system@panelalpha.com',
                'date' => Carbon::now()->subDays(14)->format('Y-m-d'),
            ],
            [
                'email' => 'admin@server-195-201-92-184.da.direct',
                'date' => null,
            ],
            [
                'email' => 'noreply@tuthost.com',
                'date' => null,
            ],
            [
                'email' => 'mail@modulesgarden.dev',
                'date' => null,
            ],
            [
                'email' => 'test@modulesgarden.dev',
                'date' => null,
            ],
        ];

        try {
            foreach ($actions as $action) {
                $this->mailbox->clean($action['email'], $action['date']);
            }
            $this->mailbox->client->disconnect();

            Log::channel('tasks')->info('Task <<'.class_basename(__CLASS__).'>> has been done.');
        } catch (Exception $exception) {
            Log::channel('tasks')->error('Task <<'.class_basename(__CLASS__).'>> failed with :', [
                'exception' => $exception,
            ]);
        }
    }
}
