<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\LeeseeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Counter;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SalesRequestsController;
use App\Http\Controllers\Profile\AvatarController;

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
    Route::get('search/users', [UsersController::class, 'search'])->name('users.search');
    Route::resource('renters', LeeseeController::class);
    Route::get('search/renters', [LeeseeController::class, 'search'])->name('renters.search');
    Route::resource('rentersrequests', SalesRequestsController::class);
    Route::get('search/rentersrequest', [SalesRequestsController::class, 'search'])->name('rentersrequests.search');
});

Route::middleware('auth')->group(function () {
    Route::get('/leesee', [LeeseeController::class, 'displayall'])->name('leesee.index');
    
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
