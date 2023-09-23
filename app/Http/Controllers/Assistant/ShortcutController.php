<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
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
}
