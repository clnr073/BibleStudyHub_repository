<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestamentController;
use App\Http\Controllers\NoteController;

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
Route::get('/testaments', [TestamentController::class, 'showTestaments'])
    ->name('testaments.show');

// ノート一覧画面
Route::get('/notes', [NoteController::class, 'index'])
    ->name('notes.index');
    
// ノート作成画面
Route::get('/notes/create', [NoteController::class, 'create'])
    ->name('notes.create');

// ノート詳細画面
Route::get('/notes/{note}', [NoteController::class, 'show'])
    ->name('notes.show');

// ノート登録処理
Route::post('/notes', [NoteController::class, 'store'])
    ->name('notes.store');
