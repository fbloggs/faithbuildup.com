<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizzesController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcomecustomized');
});


Route::get('/quiz/{id}/show', [QuizzesController::class, 'show'])
->name('quizzes.show')
->middleware('auth');

Route::put('/quiz/{id}/update', [QuizzesController::class, 'update'])
->name('quizzes.update')
->middleware('auth');

Route::get('/quiz/completed/{id}', [QuizzesController::class, 'results'])
->name('quizzes.results')
->middleware('auth');

// Use a reak controller for dashboard so we can condition display of links based on server values. 
//Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
 //   return Inertia\Inertia::render('Dashboard');
//})->name('dashboard');


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');
