<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\PlayersController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\PazinojumiController;
use App\Http\Controllers\SpelesController;
use App\Http\Controllers\VartiController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SastavaController;
use App\Http\Controllers\KalendaraController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
// main routes
Route::get('/', function () {
    return view('auth/login');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
    ->middleware('auth') // Only logged-in users can access this route
    ->name('register');
Route::post('/register', [RegisterController::class, 'store'])
    ->middleware('auth'); // Apply the same to the form submission
//notifications routes
Route::middleware('auth')->group(function () {
    Route::post('/notifications', [PazinojumiController::class, 'store'])->name('notifications.store');
    Route::post('/notifications/{id}', [PazinojumiController::class, 'update']);
});
Route::get('/notifications', [PazinojumiController::class, 'index'])->name('notifications');
Route::get('/notifications/{id}', [PazinojumiController::class, 'show'])->name('notifications.show');
Route::delete('/notifications/{id}', [App\Http\Controllers\PazinojumiController::class,'destroy'])->name('notifications.destroy');

Route::middleware('auth')->group(function () {
// teams routes
Route::get('/teams', [TeamsController::class, 'index'])->name('teams');
Route::post('/teams', [TeamsController::class, 'store'])->name('teams.store');
Route::post('/teams/{id}', [TeamsController::class, 'update']);
Route::get('/teams/{id}', [PlayersController::class, 'show'])->name('teams.show');
Route::delete('/teams/{id}', [App\Http\Controllers\TeamsController::class,'destroy'])->name('teams.destroy'); 
//players routes
Route::get('/{teamslug}/players', [PlayersController::class, 'index'])->name('players');
Route::post('{teamslug}/players', [PlayersController::class, 'store'])->name('players.store');
Route::put('players/{id}', [PlayersController::class, 'update'])->name('players.update');
Route::get('/players/{id}', [PlayersController::class, 'show'])->name('players.show');
Route::delete('{teamslug}/players/{id}', [PlayersController::class, 'destroy'])->name('players.destroy');
//comments routes
Route::get('team/{id}/comments', [CommentsController::class, 'index'])->name('comment');
Route::get('team/{teamslug}/newcomment', [CommentsController::class,'create'])->name('comment.create');
Route::post('/teams/{teamslug}/comments', [CommentsController::class, 'store'])->name('comments.store');
Route::put('/teams/{teamslug}/comments/{id}', [CommentsController::class, 'update'])->name('comment.update');
Route::delete('/teams/{teamslug}/comments/{id}', [CommentsController::class, 'destroy'])->name('comment.destroy');
//game routes
Route::get('{teamslug}/games', [SpelesController::class, 'index'])->name('games.index');
Route::post('{teamslug}/games', [SpelesController::class, 'store'])->name('games.store');
Route::get('{teamslug}/games/{id}', [SpelesController::class, 'show'])->name('games.show');
Route::post('{teamslug}/games/{id}', [SpelesController::class, 'update']);
Route::delete('{teamslug}/games/{id}', [SpelesController::class, 'destroy'])->name('speles.destroy');
Route::patch('/games/{id}/update-players/{teamslug}', [SpelesController::class, 'updatePlayers'])->name('games.updatePlayers');
//goal routes
Route::get('{teamslug}/varti', [VartiController::class, 'index'])->name('varti.index');
Route::post('{teamslug}/varti', [VartiController::class, 'store'])->name('varti.store');
Route::delete('{teamslug}/varti/{id}', [VartiController::class, 'destroy'])->name('varti.destroy');
//calendar routes
Route::get('/kalendars', [KalendaraController::class, 'index'])->name('kalendars.index');
Route::post('/kalendars/ajax', [KalendaraController::class, 'ajax'])->name('kalendars.ajax');
Route::patch('/kalendars/savePlayers/{teamslug}', [KalendaraController::class, 'savePlayers'])->name('kalendars.savePlayers');
});

//profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
