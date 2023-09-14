<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssistantRequest;
use App\Lib\Apis\Qdrant;
use App\Lib\Assistant\Assistant;
use App\Lib\Exceptions\ConnectionException;
use App\Models\Action;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use JsonException;
use Qdrant\Exception\InvalidArgumentException;

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
     * @throws InvalidArgumentException
     */
    public function chat(AssistantRequest $request): JsonResponse
    {
        $params = $request->validated();

        $this->assistant->setPrompt($params['query']);

        if (!$params['type']) {
            $params['type'] = $this->assistant->selectTypeAction();

        }

        switch ($params['type']) {
            case 'query':
                //$response = $this->assistant->query($params);
                break;
            case 'save':
                $response = $this->assistant->save($params);
                break;
            case 'forget':
                $response = $this->assistant->forget($params);
                break;
            case 'action':
                $response = $this->assistant->action($params);
                break;
        }

        return new JsonResponse([
            'data' => $response
        ]);
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
