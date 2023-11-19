<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssistantRequest;
use App\Lib\Assistant\Assistant;
use App\Lib\Exceptions\ConnectionException;
use Illuminate\Http\JsonResponse;
use JsonException;

class AssistantController extends Controller
{
    public function __construct(
        protected Assistant $assistant
    )
    {
    }

    /**
     * @param AssistantRequest $request
     * @return JsonResponse
     * @throws ConnectionException
     * @throws JsonException
     */
    public function query(AssistantRequest $request): JsonResponse
    {
        $params = $request->validated();

        $this->assistant->setQuery($params['query']);
        if (!empty($params['thread'])) {
            $this->assistant->setThread($params['thread']);
        }
        $this->assistant->setAction($params['action']);
        $this->assistant->execute();

        return new JsonResponse([
            'message' => $this->assistant->getResponse(),
            'thread' => $this->assistant->getThreadId()
        ]);
    }
}
