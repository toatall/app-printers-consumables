<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\ConsumablesController;
use App\Http\Controllers\ConsumablesCountsController;
use App\Http\Controllers\ConsumablesMoveController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\PrintersController;
use App\Http\Controllers\PrintersWorkplaceController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UsersOrganizationsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Dashboard

Route::get('/', [ConsumablesController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');


// Users

Route::get('users', [UsersController::class, 'index'])
    ->name('users')
    ->middleware('role:admin');

Route::get('users/create', [UsersController::class, 'create'])
    ->name('users.create')
    ->middleware('role:admin');

Route::post('users', [UsersController::class, 'store'])
    ->name('users.store')
    ->middleware('role:admin');

Route::get('users/{user}/edit', [UsersController::class, 'edit'])
    ->name('users.edit')
    ->middleware('auth');

Route::put('users/{user}', [UsersController::class, 'update'])
    ->name('users.update')
    ->middleware('auth');

Route::delete('users/{user}', [UsersController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware('role:admin');

Route::put('users/{user}/restore', [UsersController::class, 'restore'])
    ->name('users.restore')
    ->middleware('role:admin');

Route::get('users/organizations', [UsersOrganizationsController::class, 'index'])
    ->name('users.organizations')
    ->middleware('auth');
Route::post('users/organizations/{organization}', [UsersOrganizationsController::class, 'change'])
    ->middleware('auth');


Route::middleware('auth')->group(function() {
    // Printers
    Route::resource('printers/workplace', PrintersWorkplaceController::class);

    // ConsumableCount
    Route::get('consumables/counts', [ConsumablesCountsController::class, 'index'])->name('consumables.counts');
    Route::get('consumables/counts/create', [ConsumablesCountsController::class, 'create']);
    Route::post('consumables/counts/save', [ConsumablesCountsController::class, 'save']);
    Route::get('consumables/counts/{consumableCount}', [ConsumablesCountsController::class, 'show']);
    Route::post('consumables/counts/validate/{step}', [ConsumablesCountsController::class, 'validateConsumableCount']);
    Route::post('consumables/counts/check-exists', [ConsumablesCountsController::class, 'checkExists']);
});





// Route::get('printers', [PrintersController::class, 'index'])
//     ->name('printers')
//     ->middleware('role:admin,editor-stock,editor-local');

// Route::get('printers/create', [PrintersController::class, 'create'])
//     ->name('printers.create')
//     ->middleware('role:editor-stock,admin');

// Route::post('printers', [PrintersController::class, 'store'])
//     ->name('printers.store')
//     ->middleware('role:editor-stock,admin');

// Route::get('printers/{printer}/show', [PrintersController::class, 'show'])
//     ->name('printers.show')
//     ->middleware('role:admin,editor-stock,editor-local');

// Route::get('printers/{printer}/edit', [PrintersController::class, 'edit'])
//     ->name('printers.edit')
//     ->middleware('role:editor-stock,admin');

// Route::put('printers/{printer}', [PrintersController::class, 'update'])
//     ->name('printers.update')
//     ->middleware('role:editor-stock,admin');

// Route::delete('printers/{printer}', [PrintersController::class, 'destroy'])
//     ->name('printers.destroy')
//     ->middleware('role:editor-stock,admin');

// Route::put('printers/{printer}/restore', [PrintersController::class, 'restore'])
//     ->name('printers.restore')
//     ->middleware('role:editor-stock,admin');
    
// Consumable

// Route::resource('printers.consumables', ConsumablesController::class)    
//     ->middleware('role:editor-stock,admin');

// Consumable move

// Route::get('/consumable-moves', [ConsumablesMoveController::class, 'index'])
//     // ->middleware('role:reader,editor-stock,editor-local,admin');
//     ->middleware('auth');
// Route::post('printers/{printer}/consumables-move/{consumable}/add', [ConsumablesMoveController::class, 'add'])
//     ->middleware('role:editor-stock,admin');
// Route::post('printers/{printer}/consumables-move/{consumable}/take', [ConsumablesMoveController::class, 'takeLocal'])
//     ->middleware('role:editor-local,editor-stock,admin');
// Route::post('printers/{printer}/consumables-move/{consumable}/move-to-local', [ConsumablesMoveController::class, 'moveToLocal'])
//     ->middleware('role:editor-stock,admin');
// Route::get('printers/{printer}/consumables-move/{consumable}/history', [ConsumablesMoveController::class, 'history'])
//     ->middleware('role:reader,editor-local,editor-stock,admin');
// Route::delete('consumable-move/{id}/history', [ConsumablesMoveController::class, 'removeHistory'])
//     ->middleware('role:admin');

// Chart

// Route::get('chart', [ChartController::class, 'index'])
//     //->middleware('role:reader,editor-stock,editor-local,admin');
//     ->middleware('auth');

// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');


require_once __DIR__ . "/dictionary.php";
