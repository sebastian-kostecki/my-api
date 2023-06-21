<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssistantRequest;
use App\Lib\Assistant\Assistant;
use App\Lib\Connections\OpenAI;
use App\Lib\Connections\Pinecone;
use App\Models\Action;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
                //forget in lib/assistant
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
