<?php

use App\Jobs\TestJob;
use App\Lib\Connections\OpenAI;
use App\Models\Action;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::post('/login', function (Request $request) {
    return;
})->name('login');


Route::post('/slack/message', [\App\Http\Controllers\AssistantController::class, 'getMessage']);

Route::get('/test', function () {
//    $openAI = new \App\Lib\Assistant\Assistant();
//    $response = $openAI->describeIntention('Dodaj task do panelalpha o zbudowaniu nowego api');
//    dd(json_decode($response));

    //dd(\App\Lib\Connections\Notion\PanelAlphaIssuesTable::getIssuesList());

    $assistant = new \App\Lib\Assistant\Assistant();
    $assistant->execute('Dodaj zadanie z pracy o wykonaniu projektu o wysokim priorytecie i przypisz do issue o konfiguratorze ');
});

