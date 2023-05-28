<?php

use App\Lib\Connections\DeepL;
use App\Models\Conversation;
use Carbon\Carbon;
use FiveamCode\LaravelNotionApi\Entities\Page;
use FiveamCode\LaravelNotionApi\Query\Filters\Filter;
use FiveamCode\LaravelNotionApi\Query\Filters\Operators;
use FiveamCode\LaravelNotionApi\Query\Sorting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Collection;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', function (Request $request) {
    return;
})->name('login');

Route::get('/test', function () {
//    $response = Http::withHeaders([
//        'Authorization' => 'Bearer ' . env('SLACK_USER_TOKEN'),
//    ])->get('https://slack.com/api/users.list');
//
//    dd($response->json());

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('SLACK_USER_TOKEN'),
    ])->get("https://slack.com/api/conversations.history?channel=C059TQRA5MG&limit=1");

    if ($response->ok()) {
        $data = $response->json();
        if ($data['messages'][0]['user'] === 'U0560L191ML') {
            echo $data['messages'][0]['text'];
        }
    }
    dd($data);

    $userId = 'U0560L191ML';

//    if ($data['ok'] && isset($data['members'])) {
//        foreach ($data['members'] as $member) {
//            if ($member['profile']['email'] === 'sebastian.kostecki.programista@gmail.com') {
//                 dd($member['id'];
//            }
//        }
//    }



//    $question = "Write me about docker";
//    if (!$question) {
//        dd('No query');
//    }
//    Conversation::updateSystemPrompt();
//
//    $currentConversation = new Conversation();
//    $currentConversation->saveQuestion($question);
//
//    //pobieramy wiadomości z ostatnich 5 minut
//    $lastConversations = Conversation::where('created_at', '>=', Carbon::now()->subMinutes(5))
//        ->orWhere('system_prompt', true)
//        ->get();
//
//    //tworzymy jsona - tablica obiektow json
//    //role: ,content:
//    $messages = [];
//    foreach ($lastConversations as $conversation) {
//        for ($i = 0; $i <=1 ; $i++) {
//            $i === 0 ? $role = 'user' : $role = 'assistant';
//            $i === 0 ? $content = $conversation->question : $content = $conversation->answer;
//            if ($i === 1 && $conversation->answer === null) {
//                break;
//            }
//            $messages[] = [
//                'role' => $conversation->question === 'SYSTEM' ? 'system' : $role,
//                'content' => $conversation->question === 'SYSTEM' ? $conversation->answer : $content
//            ];
//            if ($conversation->question === 'SYSTEM') {
//                break;
//            }
//        }
//    }
//    //dd($messages);
//    //wysłanie do OpenAI
//    $openAI = new \App\Lib\Connections\OpenAI();
//    $response = $openAI->chatConversation($messages);
//
//    //aktualizacja tabeli
//    $currentConversation->saveAnswer($response);
//
//    //odpowiedz do bota na slack
//    $slackConnection = new \App\Lib\Connections\Slack('C059TQRA5MG');
//    $slackConnection->sendMessage($response);

});

