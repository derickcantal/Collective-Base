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
use App\Http\Controllers\RenterCashierController;
use App\Http\Controllers\MyCabinetController;

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
    Route::get('users/search', [UsersController::class, 'search'])->name('users.search');
    Route::resource('users', UsersController::class);
    
    Route::get('sales/search', [SalesController::class, 'search'])->name('sales.search');
    Route::get('sales/calc', [SalesController::class, 'salescalc'])->name('sales.calc');
    Route::resource('sales', SalesController::class);


    Route::get('attendance/search', [AttendanceController::class, 'search'])->name('attendance.search');
    Route::get('attendance/selectemp', [AttendanceController::class, 'selectemp'])->name('attendance.selectemp');
    Route::get('attendance/searchemp', [AttendanceController::class, 'searchemp'])->name('attendance.searchemp');
    Route::put('attendance/putemp/{users}', [AttendanceController::class, 'putemp'])->name('attendance.putemp');
    Route::resource('attendance', AttendanceController::class);
    
    Route::get('cabinet/search', [CabinetController::class, 'search'])->name('cabinet.search');
    Route::resource('cabinet', CabinetController::class);

    Route::get('branch/search', [BranchController::class, 'search'])->name('branch.search');
    Route::resource('branch', BranchController::class);

    Route::get('renters/requests/search', [RenterRequestsController::class, 'search'])->name('rentersrequests.search');
    Route::get('renters/requests/selectbranch', [RenterRequestsController::class, 'selectbranch'])->name('rentersrequests.selectbranch');
    Route::put('renters/requests/selectcabinet/{branch}', [RenterRequestsController::class, 'selectcabinet'])->name('rentersrequests.selectcabinet');
    Route::resource('rentersrequests', RenterRequestsController::class);

    Route::get('myrequest/search', [MyRequestController::class, 'search'])->name('myrequest.search');
    Route::resource('myrequest', MyRequestController::class);

    Route::get('myrental/search', [MyRequestController::class, 'search'])->name('myrental.search');
    Route::resource('myrental', MyRentalController::class);

    Route::get('mycabinet/search', [MyCabinetController::class, 'search'])->name('mycabinet.search');
    Route::get('mycabinet/sales/{cabinetsales}', [MyCabinetController::class, 'cabinetsales'])->name('mycabinet.sales');
    Route::resource('mycabinet', MyCabinetController::class);

    Route::get('saleseod', [SalesEODController::class, 'index'])->name('saleseod.index');
    Route::get('saleseod/create', [SalesEODController::class, 'create'])->name('saleseod.create');
    Route::put('saleseod', [SalesEODController::class, 'store'])->name('saleseod.store');
    Route::get('saleseod/{saleseod}/edit', [SalesEODController::class, 'edit'])->name('saleseod.edit');
    Route::put('saleseod/{saleseod}', [SalesEODController::class, 'update'])->name('saleseod.update');

    Route::get('renter/search', [RenterCashierController::class, 'search'])->name('renter.search');
    Route::get('renter/cabinet/search', [RenterCashierController::class, 'cabinetsearch'])->name('renter.cabinetsearch');
    Route::get('renter/cabinet/add', [RenterCashierController::class, 'cabinetadd'])->name('renter.cabinetadd');
    Route::get('renter/cabinet/create', [RenterCashierController::class, 'cabinetcreate'])->name('renter.cabinetcreate');
    Route::get('renter/cabinet/modify/{cabid}', [RenterCashierController::class, 'cabinetmodify'])->name('renter.cabinetmodify');
    Route::put('renter/cabinet/delete/{cabid}', [RenterCashierController::class, 'cabinetdelete'])->name('renter.cabinetdelete');
    Route::put('renter/cabinet/update/{cabid}', [RenterCashierController::class, 'cabinetupdate'])->name('renter.cabinetupdate');
    Route::post('renter/cabinet/store', [RenterCashierController::class, 'cabinetstore'])->name('renter.cabinetstore');
    Route::get('renter/create/info', [RenterCashierController::class, 'renterinfo'])->name('renter.renterinfo');
    Route::get('renter/create/login', [RenterCashierController::class, 'renterlogin'])->name('renter.renterlogin');
    Route::post('renter/create/register', [RenterCashierController::class, 'renterregister'])->name('renter.renterregister');
    
    Route::resource('renter', RenterCashierController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('renters/search', [RentersController::class, 'search'])->name('renters.search');
    Route::get('renters/selectbranch', [RentersController::class, 'selectbranch'])->name('renters.selectbranch');
    Route::put('renters/createrenter/{branchid}', [RentersController::class, 'createrenter'])->name('renters.createrenter');
    Route::put('renters/cabinet/modify/{cabinetid}', [RentersController::class, 'updatecabinet'])->name('renters.updatecabinet');
    Route::get('renters/cabinet/{cabinetid}', [RentersController::class, 'editcabinet'])->name('renters.editcabinet');
    Route::resource('renters', RentersController::class);
    
    Route::get('rental/payments/search', [RentalPaymentsController::class, 'search'])->name('rentalpayments.search');
    Route::get('rental/payments/selectrbc', [RentalPaymentsController::class, 'selectrbc'])->name('rentalpayments.selectrbc');
    Route::get('rental/payments/searchrbc', [RentalPaymentsController::class, 'searchrbc'])->name('rentalpayments.searchrbc');
    Route::put('rental/payments/putrbc/{renters}', [RentalPaymentsController::class, 'putrbc'])->name('rentalpayments.putrbc');
    Route::resource('rentalpayments', RentalPaymentsController::class);

});

Route::middleware('auth')->group(function () {
    Route::get('/reports', [ReportsController::class, 'displayall'])->name('reports.index');
    Route::get('reports/search', [ReportsController::class, 'searchhsales'])->name('reports.search');
    Route::get('/top/salesbranch', [ReportsController::class, 'topsalesbranch'])->name('reports.topsalesbranch');
    Route::get('/top/search/salesbranch', [ReportsController::class, 'searchtopsalesbranch'])->name('reports.searchtopsalesbranch');
    
     
    
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar',[AvatarController::class,'update'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/counter', Counter::class);

require __DIR__.'/auth.php';
