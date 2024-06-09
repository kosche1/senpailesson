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
Route::post('/dashboard/edit-user', [ProfileController::class, 'EditUser'])->name('dashboard.edit-user');

Route::get('/dashboard/audit-trail', [ProfileController::class, 'AuditTrail'])->name('dashboard.audit-trail');
Route::get('/dashboard/backup', [ProfileController::class, 'BackUp'])->name('dashboard.backup');
Route::get('/dashboard/restored', [ProfileController::class, 'Restored'])->name('dashboard.restored');

Route::get('/Runbackup', [ProfileController::class, 'runBackup'])->name('Runbackup');

Route::get('/Restorebackup', [ProfileController::class, 'restoreBackup'])->name('Restorebackup');
Route::get('backup/download/{file_name}', [ProfileController::class, 'download'])->name('download');
Route::get('backup/backup_delete/{file_name}', [ProfileController::class, 'backup_delete'])->name('backup_delete');

Route::get('/Search', [ProfileController::class, 'search'])->name('Search');

require __DIR__ . '/auth.php';
