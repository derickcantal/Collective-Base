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
use App\Http\Controllers\MyDashboardController;
use App\Http\Controllers\Profile\AvatarController;
use App\Http\Controllers\RentalPaymentsController;
use App\Http\Controllers\RenterRequestsController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SalesEODController;
use App\Http\Controllers\RenterCashierController;
use App\Http\Controllers\MyCabinetController;
use App\Http\Controllers\RenterCashierRentalController;
use App\Http\Controllers\EODController;
use App\Http\Controllers\UserLoginLogController;
use App\Http\Controllers\TestPagesController;


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
    Route::get('myrequest/{cabinet}/payments', [MyRequestController::class, 'create'])->name('myrequest.creates');
    Route::get('myrequest/{cabinet}/payments/process', [MyRequestController::class, 'store'])->name('myrequest.stores');
    Route::get('myrequest/{cabinet}/sales', [MyRequestController::class, 'sales'])->name('myrequest.sales');
    Route::resource('myrequest', MyRequestController::class);

    Route::get('myrental/search', [MyRentalController::class, 'search'])->name('myrental.search');
    Route::get('myrental/show/current/{cabid}/search', [MyRentalController::class, 'show_search'])->name('myrental.show_search');
    Route::get('myrental/show/previous/{cabid}/search', [MyRentalController::class, 'show_history_search'])->name('myrental.show_history_search');
    
    Route::get('myrental/show/{cabid}', [MyRentalController::class, 'show_history'])->name('myrental.show_history');
    Route::resource('myrental', MyRentalController::class);

    Route::resource('mydashboard', MyDashboardController::class);

    Route::get('mycabinet/search', [MyCabinetController::class, 'search'])->name('mycabinet.search');
    Route::get('mycabinet/sales/{cabinetsales}', [MyCabinetController::class, 'cabinetsales'])->name('mycabinet.sales');
    Route::get('mycabinet/search/{cabid}/cabinet', [MyCabinetController::class, 'cabinetsearch'])->name('mycabinet.cabinetsearch');

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
    Route::post('renter/create/register', [RenterCashierController::class, 'renterregister'])->name('renter.renterregisternew');
    Route::post('renter/create/register/{userid}', [RenterCashierController::class, 'renterregister'])->name('renter.renterregister');
    
    Route::resource('renter', RenterCashierController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('renters/search', [RentersController::class, 'search'])->name('renters.search');
    Route::get('renters/selectbranch', [RentersController::class, 'selectbranch'])->name('renters.selectbranch');
    Route::put('renters/createrenter/{branchid}', [RentersController::class, 'createrenter'])->name('renters.createrenter');
    Route::put('renters/cabinet/modify/{cabinetid}', [RentersController::class, 'updatecabinet'])->name('renters.updatecabinet');
    Route::get('renters/cabinet/{cabinetid}', [RentersController::class, 'editcabinet'])->name('renters.editcabinet');
    Route::get('renters/cabinet/status/{cabinetid}', [RentersController::class, 'statuscabinet'])->name('renters.statuscabinet');
    Route::get('renters/create/info', [RentersController::class, 'renterinfo'])->name('renters.renterinfo');
    Route::get('renters/create/login', [RentersController::class, 'renterlogin'])->name('renters.renterlogin');
    Route::post('renters/create/register', [RentersController::class, 'renterregister'])->name('renters.renterregister');
    Route::resource('renters', RentersController::class);
    
    Route::get('rental/payments/search', [RentalPaymentsController::class, 'search'])->name('rentalpayments.search');
    Route::get('rental/payments/select/renter', [RentalPaymentsController::class, 'selectrenter'])->name('rentalpayments.selectrenter');
    Route::get('rental/payments/search/renter', [RentalPaymentsController::class, 'searchrenter'])->name('rentalpayments.searchrenter');
    Route::get('rental/payments/select/{renters}/cabinet', [RentalPaymentsController::class, 'selectcabinet'])->name('rentalpayments.selectcabinet');
    Route::get('rental/payments/select/payment', [RentalPaymentsController::class, 'selectpayment'])->name('rentalpayments.selectpayment');
    Route::get('rental/payments/set/payment/month', [RentalPaymentsController::class, 'setpayment'])->name('rentalpayments.setpayment');
    Route::get('rental/payments/set/payment/month/store', [RentalPaymentsController::class, 'storesetpayment'])->name('rentalpayments.storesetpayment');
    Route::resource('rentalpayments', RentalPaymentsController::class);

    Route::get('cashier/rental/payments', [RenterCashierRentalController::class, 'search'])->name('rentercashierrental.search');
    Route::get('cashier/rental/payments/{renterid}/select/month/year', [RenterCashierRentalController::class, 'select'])->name('rentercashierrental.select');
    Route::get('cashier/rental/payments/{renterid}/new', [RenterCashierRentalController::class, 'create'])->name('rentercashierrental.creates');
    Route::get('cashier/rental/payments/{renterid}/history', [RenterCashierRentalController::class, 'show'])->name('rentercashierrental.shows');
    Route::post('cashier/rental/payments/store/{cabid}', [RenterCashierRentalController::class, 'store'])->name('rentercashierrental.stores');
    Route::resource('rentercashierrental', RenterCashierRentalController::class);

    Route::get('eod/search', [EODController::class, 'search'])->name('eod.search');
    Route::resource('eod', EODController::class);

    

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
Route::get('User/Login/Log/search', [UserLoginLogController::class, 'search'])->name('userslog.search');
Route::get('/', [UserLoginLogController::class, 'home'])->name('home');
Route::get('User/Login/Log', [UserLoginLogController::class, 'index'])->name('userslog.index');
Route::post('User/Login/Log', [UserLoginLogController::class, 'store'])->name('userslog.store');
Route::get('User/Login/Log/create', [UserLoginLogController::class, 'create'])->name('userslog.create');
Route::get('User/Login/Log/{ullid}', [UserLoginLogController::class, 'show'])->name('userslog.show');
Route::put('User/Login/Log/{ullid}', [UserLoginLogController::class, 'update'])->name('userslog.update');
Route::delete('User/Login/Log/{ullid}', [UserLoginLogController::class, 'delete'])->name('userslog.delete');
Route::get('User/Login/Log/{ullid}/edit', [UserLoginLogController::class, 'edit'])->name('userslog.edit');


Route::get('Test/Pages', [TestPagesController::class, 'index'])->name('testpages.index');

require __DIR__.'/auth.php';
