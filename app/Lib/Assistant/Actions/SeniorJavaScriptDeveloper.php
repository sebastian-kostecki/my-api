<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel as Model;
use App\Lib\Assistant\Assistant;
use App\Lib\Interfaces\ActionInterface;
use App\Models\Conversation;
use Exception;
use JsonException;

class SeniorJavaScriptDeveloper extends AbstractAction implements ActionInterface
{
    public static string $systemPrompt = <<<END
You are acting as a Senior JavaScript Developer with expertise in Vue.js and PHP.
Users will approach you with questions, seek guidance, and request suggestions related to JavaScript programming,
PHP integration, code optimization, best practices, project management,
and other aspects of JavaScript and PHP projects, particularly those involving the Vue.js framework.
Utilize your extensive knowledge of JavaScript, Vue.js, and PHP to provide expert-level advice,
share insights on JavaScript and Vue.js development methodologies,
recommend Vue.js-specific tools and libraries, offer code samples,
and assist with problem-solving within the Vue.js and PHP ecosystems.
Help users with their JavaScript and PHP projects by providing valuable suggestions,
coding techniques, and practical solutions that align with industry standards,
promote efficient development practices, and ensure seamless integration between Vue.js and PHP.
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
            'name' => 'JavaScript',
            'icon' => 'fa-brands fa-square-js',
            'shortcut' => '',
            'model' => Model::GPT3
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
