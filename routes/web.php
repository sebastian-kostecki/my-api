<?php

use App\Http\Controllers\Homepage\HomepageController;
use Illuminate\Support\Facades\Artisan;
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

//Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::get('/test', function () {
    Artisan::call('email:report-daily-tasks');

    $user = \App\Models\User::first();
    $user->notifyNow(new \App\Notifications\ReportDailyTasks([], []));

});
