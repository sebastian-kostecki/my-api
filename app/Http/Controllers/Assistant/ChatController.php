<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatRequest;
use App\Lib\Interfaces\ActionInterface;
use App\Models\Action;
use App\Models\Assistant;
use App\Models\Thread;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    /**
     * @param ChatRequest $request
     * @return JsonResponse
     */
    public function store(ChatRequest $request): JsonResponse
    {
        $params = $request->validated();

        $assistant = Assistant::findOrFail($params['assistant_id']);
        /** @var Action $action */
        $action = Action::findOrFail($params['action_id']);
        $thread = Thread::getOrCreate($params['thread_id']);
        $thread->addMessage('user', $params['input']);

        /** @var ActionInterface $operation */
        $operation = new $action->type($assistant, $thread, $params['input']);
        $result = $operation->execute();

        $thread->addMessage('assistant', $result);

        return new JsonResponse([
            'thread_id' => $thread->id,
            'message' => $result,
        ]);
    }
}
