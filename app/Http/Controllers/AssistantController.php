<?php

namespace App\Http\Controllers;

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
        $this->assistant->setAction($params['action']);
        $this->assistant->setType();
        $this->assistant->execute();

        return new JsonResponse([
            'data' => $this->assistant->getResponse()
        ]);
    }
}
