<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\StampController;
use App\Http\Controllers\ChatController;

use App\Http\Controllers\SenryuController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
use App\Http\Controllers\GoogleLoginController;

Route::get('/auth/google', [GoogleLoginController::class, 'redirectToGoogle'])
    ->name('login.google');

Route::get('/auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])
    ->name('login.google.callback');

Route::post('/stamp/store', [StampController::class, 'store'])->name('stamp.store');

Route::get('/stamps/create', [StampController::class, 'create'])->name('stamp.create');
// routes/web.php


Route::post('/upload-stamp', [StampController::class, 'store'])->name('stamp.store');


Route::resource('tweets', TweetController::class);

Route::post('/transcribe', [TweetController::class, 'transcribe'])->name('transcribe');
Route::middleware(['auth'])->group(function () {
Route::resource('tweets', TweetController::class);
});
Route::post('/tweets', [TweetController::class, 'store'])->name('tweets.store');
Route::get('/tweets', [TweetController::class, 'index'])->name('tweets.index');
Route::get('/tweets/messages', [TweetController::class, 'getMessages'])->name('tweets.messages');

Route::get('/chat', function () {
    return view('chat.index');
})->name('chat.index');

Route::get('/conversation-history', [ChatController::class, 'getConversationHistory']);


Route::post('/chat', [ChatController::class, 'handle'])->name('chat');

Route::resource('senryus', SenryuController::class);
