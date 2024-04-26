<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\ConsumablesController;
use App\Http\Controllers\ConsumablesCountsAddedController;
use App\Http\Controllers\ConsumablesCountsController;
use App\Http\Controllers\ConsumablesCountsInstalledController;
use App\Http\Controllers\ConsumablesMoveController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dictionary\ConsumablesPrintersController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\PrintersController;
use App\Http\Controllers\PrintersWorkplaceController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UsersOrganizationsController;
use App\Models\Consumable\Consumable;
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

Route::get('/', [DashboardController::class, 'index'])
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
    Route::get('printers/workplace/all', [PrintersWorkplaceController::class, 'all']);
    Route::resource('printers/workplace', PrintersWorkplaceController::class);
    Route::get('printers/workplace/list/{consumable}', [PrintersWorkplaceController::class, 'list']);
    

    // ConsumableCount    
    Route::resource('consumables/counts', ConsumablesCountsController::class)->only(['index', 'create', 'store', 'show', 'update']);
    Route::post('consumables/counts/validate', [ConsumablesCountsController::class, 'validateConsumableCount']);
    Route::post('consumables/counts/check-exists', [ConsumablesCountsController::class, 'checkExists']);
    Route::put('consumables/counts/{count}/update-organizations', [ConsumablesCountsController::class, 'updateOrganizations']);
    Route::get('consumables/counts/{count}/journal-added', [ConsumablesCountsController::class, 'journalAdded']);
    Route::get('consumables/counts/{count}/journal-installed', [ConsumablesCountsController::class, 'journalInstalled']);
    Route::get('consumables/counts/list-by-printer/{printer}', [ConsumablesCountsController::class, 'listByPrinter']);
    
    Route::resource('consumables.counts.added', ConsumablesCountsAddedController::class)->only(['index', 'destroy']);
    Route::resource('consumables.counts.installed', ConsumablesCountsInstalledController::class)->only(['index', 'store', 'destroy']);
    Route::get('consumables/counts/installed/last', [ConsumablesCountsInstalledController::class, 'last']);
    Route::get('consumables/counts/installed/master', [ConsumablesCountsInstalledController::class, 'master']);
});

// Chart

// Route::get('chart', [ChartController::class, 'index'])
//     //->middleware('role:reader,editor-stock,editor-local,admin');
//     ->middleware('auth');

// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');


require_once __DIR__ . "/dictionary.php";
