<?php

namespace App\Lib\Connections\Mailbox;

use Carbon\Carbon;
use PhpImap\Exceptions\InvalidParameterException;
use PhpImap\Mailbox as MailboxClient;

class PanelalphaCom
{
    protected MailboxClient $client;

    /**
     * @throws InvalidParameterException
     */
    public function __construct()
    {
        $this->client = new MailboxClient(
            env('PANELALPHA_EMAIL_HOSTNAME'),
            env('PANELALPHA_EMAIL_USERNAME'),
            env('PANELALPHA_EMAIL_PASSWORD')
        );
    }

    /**
     * @return void
     */
    public function moveReportsEmailToFolder(): void
    {
        if ($this->client->getImapStream()) {
            $mailIds = $this->client->searchMailbox('FROM sebastian.kostecki@panelalpha.com');
            foreach ($mailIds as $mailId) {
                $this->client->moveMail($mailId, 'INBOX.Raports');
            }
            $this->client->disconnect();
        } else {
            die('Unable to connect to the mail server.');
        }
    }

    /**
     * @return void
     */
    public function clean(): void
    {
        $email = 'no@panelalpha.com';
        $previousDay = Carbon::today()->subDay()->format('Y-m-d');

        if ($this->client->getImapStream()) {
            $mailIds = $this->client->searchMailbox("FROM $email BEFORE $previousDay");
            foreach ($mailIds as $mailId) {
                $this->client->deleteMail($mailId);
            }
            $this->client->disconnect();
        } else {
            die('Unable to connect to the mail server.');
        }
    }
}
