<?php

use App\Http\Controllers\Homepage\HomepageController;
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

/**
 * Homepage routes
 */
Route::get('/', [HomepageController::class, 'index']);


Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::post('/login', function (Request $request) {
    return;
})->name('login');


Route::post('/slack/message', [\App\Http\Controllers\AssistantController::class, 'getMessage']);

Route::get('/test', function () {
//    $request = new \App\Lib\Apis\OpenAI\Request();
//    $request->setMessages([
//        [
//            'role' => 'user',
//            'content' => 'Hello!'
//        ]
//    ]);
//    $request->chat();
//    dd($request->getContent());

});

