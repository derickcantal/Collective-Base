<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CabinetController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RentersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Counter;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MyRentalController;
use App\Http\Controllers\MyRequestController;
use App\Http\Controllers\Profile\AvatarController;
use App\Http\Controllers\RentalPaymentsController;
use App\Http\Controllers\RenterRequestsController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SalesEODController;

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
    
    Route::resource('sales', SalesController::class);
    Route::get('sales.search', [SalesController::class, 'search'])->name('sales.search');
    Route::get('sales.calc', [SalesController::class, 'salescalc'])->name('sales.calc');

    Route::resource('attendance', AttendanceController::class);
    Route::get('attendance.search', [AttendanceController::class, 'search'])->name('attendance.search');
    Route::get('attendance.selectemp', [AttendanceController::class, 'selectemp'])->name('attendance.selectemp');
    Route::get('attendance.searchemp', [AttendanceController::class, 'searchemp'])->name('attendance.searchemp');
    Route::put('attendance.putemp/{users}', [AttendanceController::class, 'putemp'])->name('attendance.putemp');

    Route::resource('cabinet', CabinetController::class);
    Route::get('cabinet.search', [CabinetController::class, 'search'])->name('cabinet.search');

    Route::resource('branch', BranchController::class);
    Route::get('branch.search', [BranchController::class, 'search'])->name('branch.search');

    Route::resource('rentersrequests', RenterRequestsController::class);
    Route::get('rentersrequests.search', [RenterRequestsController::class, 'search'])->name('rentersrequests.search');
    Route::get('rentersrequests.selectbranch', [RenterRequestsController::class, 'selectbranch'])->name('rentersrequests.selectbranch');
    Route::put('rentersrequests.selectcabinet/{branch}', [RenterRequestsController::class, 'selectcabinet'])->name('rentersrequests.selectcabinet');

    Route::resource('myrequest', MyRequestController::class);
    Route::get('myrequest.search', [MyRequestController::class, 'search'])->name('myrequest.search');
    
    Route::resource('myrental', MyRentalController::class);
    Route::get('myrental.search', [MyRequestController::class, 'search'])->name('myrental.search');

    Route::resource('saleseod', SalesEODController::class);
});

Route::middleware('auth')->group(function () {
    Route::resource('renters', RentersController::class);
    Route::get('renters.search', [RentersController::class, 'search'])->name('renters.search');
    Route::get('renters.selectbranch', [RentersController::class, 'selectbranch'])->name('renters.selectbranch');
    Route::put('renters.createrenter/{branchid}', [RentersController::class, 'createrenter'])->name('renters.createrenter');
    
    Route::resource('rentalpayments', RentalPaymentsController::class);
    Route::get('rentalpayments.search', [RentalPaymentsController::class, 'search'])->name('rentalpayments.search');
    Route::get('rentalpayments.selectrbc', [RentalPaymentsController::class, 'selectrbc'])->name('rentalpayments.selectrbc');
    Route::get('rentalpayments.searchrbc', [RentalPaymentsController::class, 'searchrbc'])->name('rentalpayments.searchrbc');
    Route::put('rentalpayments.putrbc/{renters}', [RentalPaymentsController::class, 'putrbc'])->name('rentalpayments.putrbc');
});

Route::middleware('auth')->group(function () {
    Route::get('/reports', [ReportsController::class, 'displayall'])->name('reports.index');
    Route::get('reports.search', [ReportsController::class, 'searchhsales'])->name('reports.search');
    
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar',[AvatarController::class,'update'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/counter', Counter::class);

require __DIR__.'/auth.php';
