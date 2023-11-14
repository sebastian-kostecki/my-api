<?php

namespace App\Lib\Assistant\Actions;

use App\Lib\Assistant\Assistant;
use App\Lib\Interfaces\AssistantInterface;
use App\Models\Conversation;

class DefaultAssistant extends AbstractAction implements AssistantInterface
{
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
     * @return void
     */
    public function execute(): void
    {
        dd($this);


//        try {
//            $this->assistant->conversation->saveQuestion($this->assistant->query);
//            $this->assistant->conversation->updateSystemPrompt([$this->getSystemPrompt()]);
//            $this->sendRequest();
//            $this->assistant->setResponse($this->response);
//            $this->assistant->conversation->saveAnswer($this->response);
//            $this->assistant->saveAnswerToDatabase();
//        } catch (Exception $exception) {
//            $this->assistant->setResponse($exception->getMessage());
//        }
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
