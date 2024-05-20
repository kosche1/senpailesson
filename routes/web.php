<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/dashboard/delete', [ProfileController::class, 'delete'])->name('dashboard.delete');
Route::get('/dashboard/add-user', [ProfileController::class, 'AddUser'])->name('dashboard.add-user');
Route::post('/dashboard/add-user-post', [ProfileController::class, 'AddPost'])->name('dashboard.add-user-post');
Route::get('/dashboard/edit-user', [ProfileController::class, 'EditUser'])->name('dashboard.edit-user');
Route::get('/dashboard/change', [ProfileController::class, 'change'])->name('dashboard.change');
require __DIR__.'/auth.php';
