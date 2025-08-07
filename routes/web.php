<?php

use Illuminate\Support\Facades\Route;
use Migrate\Http\Controllers\MigrationController;

Route::group([
    'prefix' => config('migrate.route_prefix'),
    'middleware' => config('migrate.middleware')
], function () {
    Route::get('/create', [MigrationController::class, 'create'])
         ->name('migrate.create');
    
    Route::post('/store', [MigrationController::class, 'store'])
         ->name('migrate.store');

    Route::get('/api/tables', [MigrationController::class, 'getTables']);
    Route::get('/api/tables/{table}/columns', [MigrationController::class, 'getTableColumns']);

});