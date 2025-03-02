<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatRequest;
use App\Lib\Exceptions\ConnectionException;
use App\Models\Action;
use App\Models\Assistant;
use App\Models\Thread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;

class ChatController extends Controller
{
    /**
     * @throws ConnectionException
     * @throws \JsonException
     */
    public function store(ChatRequest $request): JsonResponse
    {
        $params = $request->validated();

        $assistant = Assistant::findOrFail($params['assistant_id']);
        $thread = Thread::getOrMake($params['thread_id']);
        /** @var Action $action */
        $action = Action::findOrFail($params['action_id']);

        $input = $this->getInput($params);
        $operation = $action->getInstance($assistant, $action, $thread, $input);
        $result = $operation->execute();

        if ($operation->isRequireThread()) {
            $thread->save();
            $thread->addDescription($params['input']);
            $thread->addMessage('user', $params['input']);
            $thread->addMessage('assistant', $result);
        }

        return new JsonResponse([
            'thread_id' => $thread->id ?? null,
            'message' => $result,
        ]);
    }

    private function getInput(array $params): string
    {
        if (empty($params['file_code'])) {
            return $params['input'];
        }

        /** @var UploadedFile $file */
        $file = $params['file_code'];
        $content = $file->getContent();

        return $params['input']."\n###CONTEXT\n".$content;
    }
}
