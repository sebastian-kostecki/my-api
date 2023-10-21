<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel as Model;
use App\Lib\Assistant\Assistant;
use App\Lib\Interfaces\ActionInterface;
use App\Models\Conversation;
use Exception;
use JsonException;

class BugScanner extends AbstractAction implements ActionInterface
{
    public static string $systemPrompt = <<<END
Your task is to review and improve PHP code, with a specific focus on the Laravel framework.
Users will provide segments of PHP code from their Laravel-based projects and seek your expert evaluation and suggestions for enhancements.
Carefully analyze the provided code for issues related to code quality, performance, security, and adherence to Laravel best practices.
Provide detailed feedback and recommendations to help users fix problems, optimize their code, and align it with Laravel coding standards.
Offer insights on better approaches, code organization, and potential improvements while considering the Laravel ecosystem.
Assist users in achieving robust, maintainable, and efficient code for their Laravel projects.

Please provide your responses in Polish. Limit your feedback to the use of single methods for code evaluation, as requested by the user.

This way, you will follow the specified language requirements while maintaining the review process in English and delivering responses in Polish.
END;

    protected Assistant $assistant;
    protected string $response;

    public function __construct(Assistant $assistant)
    {
        $this->assistant = $assistant;
    }

    public static function getInitAction(): array
    {
        return [
            'name' => 'Code Fix',
            'icon' => 'fa-solid fa-bug',
            'shortcut' => 'CommandOrControl+Shift+F',
            'model' => Model::GPT4
        ];
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        try {
            $this->assistant->conversation->saveQuestion($this->assistant->query);
            $this->assistant->conversation->updateSystemPrompt([self::$systemPrompt]);
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
