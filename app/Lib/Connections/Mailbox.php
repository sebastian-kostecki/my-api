<?php

namespace App\Lib\Connections;

use PhpImap\Mailbox as MailboxClient;

class Mailbox
{
    protected MailboxClient $client;
    public function __construct()
    {
        $this->client = $mailbox = new MailboxClient(
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
}
