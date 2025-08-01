<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MigrationCreatorController;

Route::get('/', function () {
    return view('welcome');
});



// Route::middleware(['auth'])->group(function () {
    Route::get('/migrations/create', [MigrationCreatorController::class, 'create'])->name('migrations.create');
    Route::post('/migrations/create', [MigrationCreatorController::class, 'store'])->name('migrations.store');
// });