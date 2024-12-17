<?php

namespace App\Lib\Connections\Mailbox;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use PhpImap\Exceptions\InvalidParameterException;
use PhpImap\Mailbox as MailboxClient;

class PanelalphaCom
{
    public MailboxClient $client;

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
     * @throws Exception
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
            throw new Exception('Unable to connect to the mail server.');
        }
    }

    /**
     * @throws Exception
     */
    public function clean(string $fromEmail, ?string $beforeDate = null): void
    {
        $command = "FROM $fromEmail";
        if ($beforeDate) {
            $command .= " BEFORE $beforeDate";
        }

        $mailIds = $this->client->searchMailbox($command);
        foreach ($mailIds as $mailId) {
            $this->client->deleteMail($mailId);
        }
    }
}
