<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssistantRequest;
use App\Lib\Assistant\Assistant;
use App\Models\Action;
use Illuminate\Http\JsonResponse;

class AssistantController extends Controller
{
    public function __construct(
        protected Assistant $assistant
    )
    {
    }

    public function chat(AssistantRequest $request)
    {
        $params = $request->validated();

        if (!$params['type']) {
            $params['type'] = $this->assistant->selectType($params['query']);
        }

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
                //action in lib/assistant
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
