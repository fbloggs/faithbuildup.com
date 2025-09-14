<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizzesController;
use App\Http\Controllers\TimelinesController;
use App\Http\Controllers\UsersController;

use Inertia\Inertia;


// Use server-side view, from old version of the app, instead of the Inertia view.

Route::get('/', function () {
    return view('welcome');
});
/** 
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
}); 
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
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

Route::get('/quiz/anotheruser/{quizid}/{userid}', [QuizzesController::class, 'anotheruserpie'])
->name('quizzes.anotheruser')
->middleware('auth');

// Faith Timeline Routes (Started 20210903)
Route::get('/timeline/show', [TimelinesController::class, 'show'])
->name('timelines.show')
->middleware('auth:sanctum', 'verified');

Route::put('/timeline/update', [TimelinesController::class, 'update'])
->name('timelines.update')
->middleware('auth');

// Pie Chart results. Run results for pie chart.
Route::get('/timeline/completed/', [TimelinesController::class, 'resultschart'])
->name('timelines.results')
->middleware('auth');

Route::get('/timeline/anotheruser/{userid}', [TimelinesController::class, 'anotheruser'])
->name('timelines.anotheruser')
->middleware('auth:sanctum', 'verified');

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

