<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel as Model;
use App\Lib\Assistant\Assistant;
use App\Lib\Interfaces\ActionInterface;
use App\Models\Conversation;
use Exception;
use JsonException;

class Text2Code extends AbstractAction implements ActionInterface
{
    public static string $systemPrompt = "You are developing a code generation system tailored for web development. "
    . "Given a user prompt that describes a specific task or functionality required for a web project, "
    . "generate high-quality code in either PHP using the Laravel framework or JavaScript using the Vue.js framework, depending on the user's preference or the project requirements. "
    . "Default programming language is PHP. "
    . "The system should analyze the user's request, understand the context, and generate code that adheres to best practices and conventions of the chosen technology stack. "
    . "Ensure the code is well-structured, efficient, and follows industry-standard coding guidelines.\n"
    . "The generated code should be ready for integration into web applications and follow the principles of maintainability, security, and scalability. "
    . "Consider the user's specific requirements and provide code that addresses their needs comprehensively. "
    . "Feel free to ask for additional details or clarifications from the user if necessary to provide the most accurate and relevant code.\n"
    . "This prompt allows you to request code generation based on specific user prompts related to web development tasks, "
    . "whether it's PHP with Laravel or JavaScript with Vue.js, two popular choices in web development."
    . "Return only code without any comments and nothing more.";

    protected Assistant $assistant;
    protected string $response;

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
            'name' => 'Text2Code',
            'icon' => 'fa-solid fa-code',
            'shortcut' => '',
            'model' => Model::GPT4,
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
