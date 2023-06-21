<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssistantRequest;
use App\Lib\Assistant\Assistant;
use App\Models\Action;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
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

        if (!$params['type']) {
            $params['type'] = $this->assistant->selectType($params['query']);
        }

        Log::debug('type', [$params]);

        switch ($params['type']) {
            case 'query':
                $response = $this->assistant->query($params);
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
     * @return JsonResponse
     */
    public function getActions(): JsonResponse
    {
        $actions = Action::all();
        return new JsonResponse([
            'data' => $actions
        ]);
    }
}
