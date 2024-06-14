<?php

use App\Http\Controllers\Homepage\HomepageController;
use App\Jobs\TestJob;
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

/**
 * Homepage routes
 */
Route::get('/', [HomepageController::class, 'index']);


Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::post('/login', function (Request $request) {
    return;
})->name('login');


Route::post('/slack/message', [\App\Http\Controllers\Assistant\AssistantController::class, 'getMessage']);

Route::get('/test', function () {
    $result = "That's fine";

//    $api = new \App\Lib\Apis\Qdrant();
//    //$result = $api->listCollections();
//    $result = $api->getCollection('myapi');

    $model = 'text-embedding-3-small';
    $input = "Something goes wrong";
    $result = \App\Lib\Apis\OpenAI::factory()->embeddings($model, $input);




    dd($result);
});

