<?php

namespace App\Http\Controllers;

use App\Jobs\SaveResource;
use App\Lib\Assistant\Actions\Translate;
use App\Lib\Assistant\Assistant;
use DeepL\DeepLException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShortcutController extends Controller
{
    public function __construct(
        protected Assistant $assistant
    )
    {
    }

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

        $translator = new Translate($this->assistant);
        $translator->assistant->setQuery($request->input('text'));
        $translator->assistant->setAction(Translate::class);
        $translator->execute();
        $translatedText = $translator->assistant->getResponse();

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
        $chunkedLines = array_chunk($lines, 100);

        foreach ($chunkedLines as $chunkedLine) {
            SaveResource::dispatch($title, $chunkedLine);
        }

        return new JsonResponse([
            'status' => 'started'
        ]);
    }
}
