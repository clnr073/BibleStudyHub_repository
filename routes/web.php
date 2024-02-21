<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestamentController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ConnectionController;

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

Route::controller(TestamentController::class)->middleware('auth')->group(function () {
    Route::get('/testaments', 'index')->name('testaments.index');
    Route::get('/testaments/volume{volume}', 'displayVolumeWithContents')->name('displayVolumeWithContents');
    Route::get('/testaments/volume{volume}/chapter{chapter}', 'displayChapterWithContents')->name('displayChapterWithContents');
});

Route::controller(NoteController::class)->middleware('auth')->group(function () {
    Route::get('/notes', 'index')->name('notes.index');
    Route::get('/notes/create', 'create')->name('create');
    Route::get('/notes/{note}', 'show')->name('show');
    Route::post('/notes', 'store')->name('store');
    Route::get('/notes/{note}/edit', 'edit')->name('edit');
    Route::put('/notes/{note}', 'update')->name('update');
    Route::delete('/notes/{note}', 'delete')->name('delete');
});

Route::controller(CommentController::class)->middleware('auth')->group(function () {
    Route::get('/notes/{note}/comments', 'index')->name('comments.index');
    Route::post('/notes/{note}/comments', 'store')->name('comments.store');
    Route::get('/notes/{note}/comments/{comment}/edit', 'edit')->name('comments.edit');
    Route::put('/notes/{note}/comments/{comment}', 'update')->name('comments.update');
    Route::delete('/notes/{note}/comments/{comment}', 'delete')->name('comments.delete');
});

Route::controller(TagController::class)->middleware('auth')->group(function () {
    Route::get('/tags', 'index')->name('tags.index');
    Route::post('/tags', 'store')->name('tags.store');
    Route::delete('/tags/{tag}', 'delete')->name('tags.delete');
    Route::get('/tags/{tag}/edit', 'edit')->name('tags.edit');
    Route::put('/tags/{tag}', 'update')->name('tags.update');
});

Route::controller(ConnectionController::class)->middleware('auth')->group(function () {
    Route::get('/connections', 'index')->name('connections.index');
    Route::post('/connections/approval', 'approvalUserRequest')->name('approvalUserRequest');
    Route::put('/connections/unfriend', 'unFriend')->name('unFriend');
    Route::post('/connections/follow', 'followUser')->name('followUser');
});