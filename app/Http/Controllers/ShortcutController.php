<?php

namespace App\Http\Controllers;

use App\Jobs\SaveResource;
use App\Lib\Assistant\Actions\Translate;
use DeepL\DeepLException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShortcutController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws DeepLException
     */
    public function translate(Request $request): JsonResponse
    {
        $request->validate([
            'text' => 'string|required'
        ]);

        $translator = new Translate($request->input('text'));
        $translatedText = $translator->execute();
        return new JsonResponse([
            'data' => $translatedText
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function saveResource(Request $request): JsonResponse
    {
        $request->validate([
            'text' => 'required'
        ]);
        $text = $request->input('text');

        $lines = explode("\n", $text['text']);
        $title = $text['title'];
        $chunkedLines = array_chunk($lines, 1000);

        foreach ($chunkedLines as $chunkedLine) {
            SaveResource::dispatch($title, $chunkedLine);
        }

        return new JsonResponse([
            'status' => 'started'
        ]);
    }
}
