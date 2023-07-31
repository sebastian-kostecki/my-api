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
    return view('developer');
});

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::post('/login', function (Request $request) {
    return;
})->name('login');


Route::post('/slack/message', [\App\Http\Controllers\AssistantController::class, 'getMessage']);

Route::get('/test', function () {
    \Illuminate\Support\Facades\Mail::to('sebastian@kostecki.pl')->send(new \App\Mail\ReportDailyTasks(['asdas'], ['asdas']));
});

