<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizzesController;
use App\Http\Controllers\TimelinesController;
use App\Http\Controllers\UsersController;
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
    return view('welcome');
});


Route::get('/quiz/{quizid}/show', [QuizzesController::class, 'show'])
->name('quizzes.show')
->middleware('auth:sanctum', 'verified');

Route::put('/quiz/{quizid}/update', [QuizzesController::class, 'update'])
->name('quizzes.update')
->middleware('auth');

// Pie Chart results. Run resultsbar for bar chart.
Route::get('/quiz/completed/{quizid}', [QuizzesController::class, 'resultspie'])
->name('quizzes.results')
->middleware('auth');


// Faith Timeline Routes (Started 20210903)
Route::get('/timeline/show', [TimelinesController::class, 'show'])
->name('timelines.show')
->middleware('auth:sanctum', 'verified');

Route::get('/timeline/showuser/{ user }', [TimelinesController::class, 'showuser'])
->name('timelines.showuser')
->middleware('auth:sanctum', 'verified');

Route::put('/timeline/update', [TimelinesController::class, 'update'])
->name('timelines.update')
->middleware('auth');

// Pie Chart results. Run results for pie chart.
Route::get('/timeline/completed/', [TimelinesController::class, 'resultschart'])
->name('timelines.results')
->middleware('auth');

// Use a real controller for dashboard so we can condition display of links based on server values.
//Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
 //   return Inertia\Inertia::render('Dashboard');
//})->name('dashboard');

// Users list so Admin can look at anyones info
Route::get('/users', [UsersController::class, "index"])->name('users.index')->middleware('auth');
Route::get('/users/showquiz/{userid}', [UsersController::class, "showquiz"])->name('user.showquiz')->middleware('auth');
// Pie Chart results. Run results for pie chart.
Route::get('/users/timeline/{userid}', [TimelinesController::class, 'resultschart'])
->name('user.showtimeline')
->middleware('auth');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');
