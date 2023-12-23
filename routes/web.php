<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RentersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Counter;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Profile\AvatarController;
use App\Http\Controllers\RentalPaymentsController;
use App\Http\Controllers\RenterRequestsController;

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

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'displayall'])->name('dashboard.index');
});
Route::middleware('auth')->group(function () {
    Route::resource('users', UsersController::class);
    Route::get('users.search', [UsersController::class, 'search'])->name('users.search');
    
    Route::resource('rentersrequests', RenterRequestsController::class);
    Route::get('rentersrequests.search', [RenterRequestsController::class, 'search'])->name('rentersrequests.search');
});

Route::middleware('auth')->group(function () {
    Route::resource('renters', RentersController::class);
    Route::get('renters.search', [RentersController::class, 'search'])->name('renters.search');
    
    Route::resource('rentalpayments', RentalPaymentsController::class);
    Route::get('rentalpayments.search', [RentalPaymentsController::class, 'search'])->name('rentalpayments.search');
});

Route::middleware('auth')->group(function () {
    Route::get('/reports', [ReportsController::class, 'displayall'])->name('reports.index');
    
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar',[AvatarController::class,'update'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/counter', Counter::class);

require __DIR__.'/auth.php';
