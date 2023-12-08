<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestamentController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\CommentController;

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

// 聖書一覧画面
Route::get('/testaments/{volume_id?}/{chapter?}', [TestamentController::class, 'displayChapterWithContents'])
    ->name('testaments.index');

// ノート一覧画面
Route::get('/notes', [NoteController::class, 'index'])
    ->name('notes.index');
    
// ノート作成画面
Route::get('/notes/create', [NoteController::class, 'create'])
    ->name('notes.create');

// ノート詳細画面
Route::get('/notes/{note}', [NoteController::class, 'show'])
    ->name('notes.show');
Route::post('/notes/{note}', [CommentController::class, 'store']);

// ノート登録処理
Route::post('/notes', [NoteController::class, 'store'])
    ->name('notes.store');
    
// ノート編集処理
Route::get('/notes/{note}/edit', [NoteController::class, 'edit'])
    ->name('notes.edit');
Route::put('/notes/{note}', [NoteController::class, 'update'])
    ->name('notes.update');
    
// ノート削除処理
Route::delete('/notes/{note}', [NoteController::class, 'delete'])
    ->name('notes.delete');
