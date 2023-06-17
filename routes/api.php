<?php

use App\Http\Controllers\AssistantController;
use App\Http\Controllers\ShortcutController;
use App\Http\Controllers\TextController;
use App\Http\Controllers\TranslationController;
use App\Lib\Connections\Qdrant;
use App\Models\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/login', function (Request $request) {
        return;
    })->name('login');

    Route::post('/chat', [AssistantController::class, 'chat']);


    /**
     * Shortcuts
     */
    Route::post('/shortcut/translate', [ShortcutController::class, 'translate']);
    Route::post('/shortcut/save-note', [ShortcutController::class, 'saveResource']);

    /**
     * Assistant
     */
    Route::post('/assistant/prompt', [AssistantController::class, 'ask']);
    Route::post('/assistant/answer', [AssistantController::class, 'get']);
    Route::get('/assistant/actions', [AssistantController::class, 'getActions']);

    Route::post('/assistant/test', function (Request $request) {
        $params = $request->validate([
            'query' => 'string',
            'type' => 'string',
            'group' => 'string',
            'action' => 'string',
            'record_id' => 'integer'
        ]);

        switch ($params['type']) {
            case 'query':
                //wyszukiwanie informacji
                break;
            case 'save':
                /**
                 * Zapisywanie danych
                 *
                 * wygenerowanie informacji pomocniczych: title + tagi
                 * zapisanie do bazy MySQL
                 * zapisanie do bazy wektorowej
                 */

                $language = detectLanguage($params['query']);
                if ($language !== 'pl') {
                    $params['query'] = $this->openAI->translateToPolish($params['query']);
                }
                $openAI = new \App\Lib\Connections\OpenAI();
                $tags = json_decode($openAI->generateTags($params['query']));

                $resource = new Resource();
                $resource->content = $params['query'];
                $resource->category = $params['group'];
                $resource->tags = $tags->tags;
                $resource->save();

                $text = $resource->content . " " . implode(',', $resource->tags);
                $embedding = $openAI->createEmbedding($text);

                $vectorDatabase = new Qdrant('test');
                $vectorDatabase->insertVector($resource->id, $embedding, [
                    'id' => $resource->id,
                    'tags' => implode(',', $resource->tags)
                ]);
                return new JsonResponse([
                    'data' => 'Dodano'
                ]);
                break;
            case 'forget':
                /**
                 * Zapominanie informacji
                 *
                 * usuwamy dane z qdrant
                 * usuwamy dane z bazy danych
                 */
                
                $resource = Resource::findOrFail($params['record_id']);
                $resource->delete();

                $vectorDatabase = new Qdrant('test');
                $vectorDatabase->deleteVector($params['record_id']);

                return new JsonResponse([
                    'data' => 'Usunięto'
                ]);
                break;
        }
    });
});

