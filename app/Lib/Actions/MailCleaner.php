<?php

namespace App\Lib\Actions;

use App\Lib\Interfaces\ActionInterface;
use App\Lib\Traits\ShouldThread;
use App\Models\EmailAccount;
use PhpImap\Mailbox as MailboxClient;
use JsonException;

class MailCleaner extends AbstractAction implements ActionInterface
{
    use ShouldThread;

    public const NAME = 'Mail Cleaner';
    public const ICON = 'fa-solid fa-inbox';

    public function execute(): string
    {
        //usuń maile od pawel@panelalpha.com w skrzynce sebastian.kostecki@panelalpha.com

        $params = $this->getParams();

        $emailAccount = EmailAccount::getModel($params['account']);




        return "";
    }

    /**
     * @return array{
     *     sender: string,
     *     account: string
     * }
     * @throws JsonException
     */
    private function getParams(): array
    {
        $model = 'gpt-3.5-turbo';
        $messages = [
            [
                'role' => 'system',
                'content' => "Na podstawie tekstu użytkownika przypisz adresy mailowe do określonych ról w formacie JSON.\nExample: 'Usuń maile od joe@doe.com w skrzynce mike@bowl.com' {\"account\": \"mike@bowl.com\", \"sender\": \"joe@doe.com\"}"
            ],
            [
                'role' => 'user',
                'content' => $this->input,
            ]
        ];
        $params = [
            'response_format' => [
                'type' => 'json_object'
            ]
        ];
        $result = \App\Lib\Apis\OpenAI::factory()->completion($model, $messages, $params);
        return json_decode($result, true, 512, JSON_THROW_ON_ERROR);
    }
}
