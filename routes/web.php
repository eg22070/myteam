<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\PlayersController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\PazinojumiController;
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
    return view('auth/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/register', function () {
    return view('auth/register');
});
Route::middleware('auth')->group(function () {
    Route::post('/notifications', [PazinojumiController::class, 'store'])->name('notifications.store');
    Route::post('/notifications/{id}', [PazinojumiController::class, 'update']);
});
Route::get('/notifications', [PazinojumiController::class, 'index'])->name('notifications');
Route::get('/tnotifications/{id}', [PazinojumiController::class, 'show'])->name('notifications.show');
Route::delete('/notifications/{id}', [App\Http\Controllers\PazinojumiController::class,'destroy'])->name('notifications.destroy');

Route::middleware('auth')->group(function () {

Route::resource('/teams', TeamsController::class, ['except' => ['index', 'update']]);
Route::get('/teams', [TeamsController::class, 'index'])->name('teams');
Route::post('/teams', [TeamsController::class, 'store'])->name('teams.store');
Route::post('/teams/{id}', [TeamsController::class, 'update']);
Route::get('/teams/{id}', [PlayersController::class, 'show'])->name('teams.show');
Route::delete('/teams/{id}', [App\Http\Controllers\TeamsController::class,'destroy'])->name('teams.destroy'); 

Route::resource('/players', PlayersController::class, ['except' => ['index', 'create', 'update']]);
Route::get('/{teamslug}/players', [PlayersController::class, 'index'])->name('players');
Route::get('{teamslug}/players/create', [PlayersController::class,'create']);
Route::post('{teamslug}/players', [PlayersController::class, 'store']);
Route::post('/players/{id}', [PlayersController::class, 'update']);
Route::get('/players/{id}', [PlayersController::class, 'show'])->name('players.show');


Route::resource('/players/{id}/comments', CommentsController::class, ['except' => ['index', 'create']]);
Route::get('/players/{id}/comments', [CommentsController::class, 'index'])->name('comment');
Route::get('/players/{id}/newcomment', [CommentsController::class,'create'])->name('comment.create');
Route::post('/players/{id}/comments', [CommentsController::class, 'store']);
Route::delete('/players/{id}/comments', [App\Http\Controllers\CommentsController::class,'destroy'])->name('comment.destroy'); 

// Main calendar view
Route::get('/calendar', [TrainingController::class, 'index'])->name('calendar');

// Fetch training events based on selected team
Route::get('/fetch-events/{teamId}', [TrainingController::class, 'fetchEvents']);

// Store a new training event
Route::post('/store-event', [TrainingController::class, 'storeEvent']);

// Delete a training event
Route::delete('/delete-event/{id}', [TrainingController::class, 'deleteEvent']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
