<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel as Model;
use App\Lib\Assistant\Assistant;
use App\Lib\Interfaces\ActionInterface;
use App\Models\Conversation;
use Exception;
use JsonException;

class SeniorPhpDeveloper extends AbstractAction implements ActionInterface
{
    public static string $systemPrompt = <<<END
You are acting as a Senior PHP Developer with a strong focus on the Laravel framework.
Users will approach you with questions, seek guidance, and request suggestions related to PHP programming,
code optimization, best practices, project management, and other aspects of PHP projects, specifically in the context of Laravel.
Leverage your expertise in PHP and Laravel to provide expert-level advice,
share insights on Laravel development methodologies, recommend Laravel-specific tools and libraries,
offer Laravel code samples, and assist with problem-solving within the Laravel ecosystem.
Help users with their PHP projects by providing valuable suggestions, coding techniques,
and practical solutions that adhere to Laravel conventions, promote efficient development practices,
and ensure security and scalability.
END;

    protected Assistant $assistant;
    protected string $response;

    /**
     * @param Assistant $assistant
     */
    public function __construct(Assistant $assistant)
    {
        $this->assistant = $assistant;
    }

    /**
     * @return array{
     *     name: string,
     *     icon: string,
     *     shortcut: string,
     *     model: Model
     * }
     */
    public static function getInitAction(): array
    {
        return [
            'name' => 'PHP',
            'icon' => 'fa-brands fa-php',
            'shortcut' => '',
            'model' => Model::GPT3,
            'system_prompt' => self::$systemPrompt
        ];
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        try {
            $this->assistant->conversation->saveQuestion($this->assistant->query);
            $this->assistant->conversation->updateSystemPrompt([$this->getSystemPrompt()]);
            $this->sendRequest();
            $this->assistant->setResponse($this->response);
            $this->assistant->conversation->saveAnswer($this->response);
            $this->assistant->saveAnswerToDatabase();
        } catch (Exception $exception) {
            $this->assistant->setResponse($exception->getMessage());
        }
    }

    /**
     * @return void
     * @throws JsonException
     */
    protected function sendRequest(): void
    {
        $model = $this->getModel();
        $messages = Conversation::getConversationsLastFiveMinutes();
        $this->assistant->api->chat()->create($model, $messages);
        $this->response = $this->assistant->api->chat()->getResponse();
    }
}
