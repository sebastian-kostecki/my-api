<?php

namespace App\Console\Commands\Sync;

use App\Models\Recipient;
use Illuminate\Console\Command;

class SyncRecipients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:recipients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronizing recipients';

    protected array $recipients = [
        'report' => [
            'Konrad Keck' => 'konrad.keck@panelalpha.com',
            'Paweł Kozioł' => 'pawel.koziol@panelalpha.com',
            'Sebastian Kostecki' => 'sebastian.kostecki@panelalpha.com',
        ],
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach ($this->recipients as $type => $recipients) {
            foreach ($recipients as $name => $email) {
                $recipient = Recipient::where('type', $type)->where('name', $name)->first();
                if (! $recipient) {
                    $recipient = new Recipient;
                }
                $recipient->name = $name;
                $recipient->email = $email;
                $recipient->type = $type;
                $recipient->save();
            }
        }
    }
}
