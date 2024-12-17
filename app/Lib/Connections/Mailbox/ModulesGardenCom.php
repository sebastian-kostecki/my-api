<?php

namespace App\Lib\Connections\Mailbox;

use Carbon\Carbon;
use PhpImap\Exceptions\InvalidParameterException;
use PhpImap\Mailbox as MailboxClient;

class ModulesGardenCom
{
    public MailboxClient $client;

    /**
     * @throws InvalidParameterException
     */
    public function __construct()
    {
        $this->client = new MailboxClient(
            env('MODULESGARDEN_EMAIL_HOSTNAME'),
            env('MODULESGARDEN_EMAIL_USERNAME'),
            env('MODULESGARDEN_EMAIL_PASSWORD')
        );
    }

    /**
     * @return array[]
     */
    public function emailToDelete(): array
    {
        return [
            [
                'email' => 'git@modulesgarden.com',
                'period' => Carbon::today()->subWeek()->format('Y-m-d'),
            ],
            [
                'email' => 'git@rsstudio.net',
                'period' => Carbon::today()->subWeek()->format('Y-m-d'),
            ],
            [
                'email' => 'development@modulesgarden.com',
                'period' => Carbon::today()->subMonth()->format('Y-m-d'),
            ],
            [
                'email' => 'test@modulesgarden.tech',
                'period' => Carbon::today()->subWeek()->format('Y-m-d'),
            ],
            [
                'email' => 'system@panelalpha.com',
                'period' => Carbon::today()->subMonth()->format('Y-m-d'),
            ],
            [
                'email' => 'noreply@dev-alpha.cloud',
                'period' => Carbon::today()->format('Y-m-d'),
            ],
            [
                'email' => 'test@modulesgarden.com',
                'period' => Carbon::today()->format('Y-m-d'),
            ],
            [
                'email' => 'sebastian.kostecki@panelalpha.com',
                'period' => Carbon::today()->format('Y-m-d'),
            ],
            [
                'email' => 'mail@modulesgarden.dev',
                'period' => Carbon::today()->format('Y-m-d'),
            ],
            [
                'email' => 'system@system.smartsheet.com',
                'period' => Carbon::today()->format('Y-m-d'),
            ],
        ];
    }

    /**
     * @return array[]
     */
    protected function emailsToMove(): array
    {
        return [
            [
                'email' => 'jadwiga@modulesgarden.com',
                'folder' => 'INBOX.Firma.Jadzia',
            ],
            [
                'email' => 'grzegorz.so@modulesgarden.com',
                'folder' => 'INBOX.Firma.WHMCS',
            ],
            [
                'email' => 'mariusz@modulesgarden.com',
                'folder' => 'INBOX.Firma.Mariusz',
            ],
            [
                'email' => 'konrad.keck@inbs.software',
                'folder' => 'INBOX.Firma.Konrad',
            ],
            [
                'email' => 'contact@panelalpha.com',
                'folder' => 'INBOX.Firma.Konrad',
            ],
            [
                'email' => 'konrad@modulesgarden.com',
                'folder' => 'INBOX.Firma.Konrad',
            ],
            [
                'email' => 'konrad@modulesgarden.com',
                'folder' => 'INBOX.Firma.Konrad',
            ],
            [
                'email' => 'git@modulesgarden.com',
                'folder' => 'INBOX.PanelAlpha',
            ]
        ];
    }

    public function clean(): void
    {
        foreach ($this->emailToDelete() as $email) {
            if ($this->client->getImapStream()) {
                $mailIds = $this->client->searchMailbox('FROM '.$email['email'].' BEFORE '.$email['period']);
                foreach ($mailIds as $mailId) {
                    $this->client->deleteMail($mailId);
                }
            } else {
                exit('Unable to connect to the mail server.');
            }
        }

        foreach ($this->emailsToMove() as $item) {
            if ($this->client->getImapStream()) {
                $mailIds = $this->client->searchMailbox('FROM '.$item['email']);
                foreach ($mailIds as $mailId) {
                    $this->client->moveMail($mailId, $item['folder']);
                }
            } else {
                exit('Unable to connect to the mail server.');
            }
        }
        $this->client->disconnect();
    }
}
