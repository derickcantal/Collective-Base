<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Reports\ReportAttendanceController;
use App\Http\Controllers\Reports\ReportRentalsController;
use App\Http\Controllers\Reports\ReportSalesController;
use App\Http\Controllers\Reports\ReportTopSalesController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Counter;
use App\Http\Controllers\Manage\ManageBranchController;
use App\Http\Controllers\Manage\ManageCabinetController;
use App\Http\Controllers\Manage\ManageRenterController;
use App\Http\Controllers\Manage\ManageAllRenterController;
use App\Http\Controllers\Manage\ManageUserController;
use App\Http\Controllers\Manage\ManageMailboxController;
use App\Http\Controllers\Manage\ManageCashierRenterController;
use App\Http\Controllers\Transaction\TransactionAttendanceController;
use App\Http\Controllers\Transaction\TransactionCabinetSalesController;
use App\Http\Controllers\Transaction\TransactionRentalController;
use App\Http\Controllers\Transaction\TransactionSalesController;
use App\Http\Controllers\Transaction\TransactionEODController;
use App\Http\Controllers\Dashboard\DashboardOverviewController;
use App\Http\Controllers\Dashboard\DashboardRentalsController;
use App\Http\Controllers\Dashboard\DashboardSalesController;
use App\Http\Controllers\Dashboard\DashboardAttendanceController;
use App\Http\Controllers\SalesEODController;

use App\Http\Controllers\Profile\AvatarController;
use App\Http\Controllers\UserLoginLogController;
use App\Http\Controllers\TestPagesController;
use App\Http\Controllers\CBMailController;


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

    Route::get('/dashboard/overview', [DashboardOverviewController::class, 'index'])->name('dashboardoverview.index');
    Route::get('/dashboard/overview/search', [DashboardOverviewController::class, 'search'])->name('dashboardoverview.search');

    Route::get('/dashboard/rentals', [DashboardRentalsController::class, 'index'])->name('dashboardrentals.index');
    Route::get('/dashboard/rentals/search', [DashboardRentalsController::class, 'search'])->name('dashboardrentals.search');

    Route::get('/dashboard/sales', [DashboardSalesController::class, 'index'])->name('dashboardsales.index');
    Route::get('/dashboard/sales/search', [DashboardSalesController::class, 'search'])->name('dashboardsales.search');

    Route::get('/dashboard/attendance', [DashboardAttendanceController::class, 'index'])->name('dashboardattendance.index');
    Route::get('/dashboard/attendance/search', [DashboardAttendanceController::class, 'search'])->name('dashboardattendance.search');

});

Route::middleware('auth')->group(function () {
    Route::get('/manage/branch', [ManageBranchController::class, 'index'])->name('managebranch.index');
    Route::post('/manage/branch', [ManageBranchController::class, 'store'])->name('managebranch.store');
    Route::get('/manage/branch/create', [ManageBranchController::class, 'create'])->name('managebranch.create');
    Route::get('/manage/branch/search', [ManageBranchController::class, 'search'])->name('managebranch.search');
    Route::get('/manage/branch/{branch}', [ManageBranchController::class, 'show'])->name('managebranch.show');
    Route::patch('/manage/branch/{branch}', [ManageBranchController::class, 'update'])->name('managebranch.update');
    Route::delete('/manage/branch/{branch}', [ManageBranchController::class, 'destroy'])->name('managebranch.destroy');
    Route::get('/manage/branch/{branch}/edit', [ManageBranchController::class, 'edit'])->name('managebranch.edit');

    Route::get('/manage/renters/branch/select', [ManageRenterController::class, 'index'])->name('managerenter.index');
    Route::get('/manage/renters/branch/search', [ManageRenterController::class, 'search'])->name('managerenter.search');
    Route::post('/manage/renters/branch', [ManageRenterController::class, 'store'])->name('managerenter.store');
    Route::get('/manage/renters/branch/{branchid}/create', [ManageRenterController::class, 'create'])->name('managerenter.create');
    Route::get('/manage/renters/branch/{branchid}/search', [ManageRenterController::class, 'searchrenter'])->name('managerenter.searchrenter');
    Route::get('/manage/renters/{renters}/branch/{branchid}/info', [ManageRenterController::class, 'show'])->name('managerenter.show');
    Route::patch('/manage/renters/branch/{renters}', [ManageRenterController::class, 'update'])->name('managerenter.update');
    Route::delete('/manage/renters/branch/{renters}', [ManageRenterController::class, 'destroy'])->name('managerenter.destroy');
    Route::get('/manage/renters/{renters}/branch/{branchid}/edit', [ManageRenterController::class, 'edit'])->name('managerenter.edit');
    Route::get('/manage/renters/branch/{branchid}/list', [ManageRenterController::class, 'renterslist'])->name('managerenter.renterslist');

    Route::get('/manage/renters/list', [ManageAllRenterController::class, 'allrenters'])->name('manageallrenter.allrenters');
    Route::get('/manage/renters/search', [ManageAllRenterController::class, 'search'])->name('manageallrenter.search');
    Route::post('/manage/renters', [ManageAllRenterController::class, 'store'])->name('manageallrenter.store');
    Route::get('/manage/renters/create', [ManageAllRenterController::class, 'create'])->name('manageallrenter.create');
    Route::get('/manage/renters/{renters}/info', [ManageAllRenterController::class, 'show'])->name('manageallrenter.show');
    Route::patch('/manage/renters/{renters}', [ManageAllRenterController::class, 'update'])->name('manageallrenter.update');
    Route::delete('/manage/renters/{renters}', [ManageAllRenterController::class, 'destroy'])->name('manageallrenter.destroy');
    Route::get('/manage/renters/{renters}/edit', [ManageAllRenterController::class, 'edit'])->name('manageallrenter.edit');
    Route::get('/manage/renters/{renters}/sendmail', [ManageAllRenterController::class, 'sendmail'])->name('manageallrenter.sendmail');

    Route::get('/manage/branch/cabinet/select', [ManageCabinetController::class, 'index'])->name('managecabinet.index');
    Route::get('/manage/branch/{branchid}/cabinet/list', [ManageCabinetController::class, 'cabinetlist'])->name('managecabinet.cabinetlist');
    Route::post('/manage/branch/{branchid}/cabinet', [ManageCabinetController::class, 'store'])->name('managecabinet.store');
    Route::get('/manage/branch/{branchid}/cabinet/create', [ManageCabinetController::class, 'create'])->name('managecabinet.create');
    Route::get('/manage/branch/cabinet/search', [ManageCabinetController::class, 'search'])->name('managecabinet.search');
    Route::get('/manage/branch/{branchid}/cabinet/search', [ManageCabinetController::class, 'searchcabinet'])->name('managecabinet.searchcabinet');
    Route::get('/manage/branch/cabinet/{cabinet}', [ManageCabinetController::class, 'show'])->name('managecabinet.show');
    Route::patch('/manage/branch/cabinet/{cabinet}', [ManageCabinetController::class, 'update'])->name('managecabinet.update');
    Route::delete('/manage/branch/cabinet/{cabinet}', [ManageCabinetController::class, 'destroy'])->name('managecabinet.destroy');
    Route::get('/manage/branch/cabinet/{cabinet}/edit', [ManageCabinetController::class, 'edit'])->name('managecabinet.edit');

    Route::get('/manage/user', [ManageUserController::class, 'index'])->name('manageuser.index');
    Route::post('/manage/user', [ManageUserController::class, 'store'])->name('manageuser.store');
    Route::get('/manage/user/create', [ManageUserController::class, 'create'])->name('manageuser.create');
    Route::get('/manage/user/search', [ManageUserController::class, 'search'])->name('manageuser.search');
    Route::get('/manage/user/{user}', [ManageUserController::class, 'show'])->name('manageuser.show');
    Route::patch('/manage/user/{user}', [ManageUserController::class, 'update'])->name('manageuser.update');
    Route::delete('/manage/user/{user}', [ManageUserController::class, 'destroy'])->name('manageuser.destroy');
    Route::get('/manage/user/{user}/edit', [ManageUserController::class, 'edit'])->name('manageuser.edit');

    Route::get('/manage/mailbox', [ManageMailboxController::class, 'index'])->name('managemailbox.index');
    Route::post('/manage/mailbox', [ManageMailboxController::class, 'store'])->name('managemailbox.store');
    Route::get('/manage/mailbox/create', [ManageMailboxController::class, 'create'])->name('managemailbox.create');
    Route::get('/manage/mailbox/search', [ManageMailboxController::class, 'search'])->name('managemailbox.search');
    Route::get('/manage/mailbox/{mailbox}', [ManageMailboxController::class, 'show'])->name('managemailbox.show');
    Route::patch('/manage/mailbox/{mailbox}', [ManageMailboxController::class, 'update'])->name('managemailbox.update');
    Route::delete('/manage/mailbox/{mailbox}', [ManageMailboxController::class, 'destroy'])->name('managemailbox.destroy');
    Route::get('/manage/mailbox/{mailbox}/edit', [ManageMailboxController::class, 'edit'])->name('managemailbox.edit');

});

Route::middleware('auth')->group(function () {
    Route::get('/manage/cashier/renter/search', [ManageCashierRenterController::class, 'search'])->name('managecr.search');
    Route::get('/manage/cashier/renter', [ManageCashierRenterController::class, 'index'])->name('managecr.index');
    Route::post('/manage/cashier/renter', [ManageCashierRenterController::class, 'store'])->name('managecr.store');
    Route::get('/manage/cashier/renter/create', [ManageCashierRenterController::class, 'create'])->name('managecr.create');
    Route::get('/manage/cashier/renter/{renters}', [ManageCashierRenterController::class, 'show'])->name('managecr.show');
    Route::put('/manage/cashier/renter/{renters}', [ManageCashierRenterController::class, 'update'])->name('managecr.update');
    Route::delete('/manage/cashier/renter/{renters}', [ManageCashierRenterController::class, 'destroy'])->name('managecr.destroy');
    Route::get('/manage/cashier/renter/{renters}/edit', [ManageCashierRenterController::class, 'edit'])->name('managecr.edit');
    Route::get('/manage/cashier/renter/cabinet/{renters}', [ManageCashierRenterController::class, 'cabinetlist'])->name('managecr.cabinetlist');
    Route::get('/manage/cashier/renter/{rentersid}/cabinet/{cabinetid}/search', [ManageCashierRenterController::class, 'cabinetsearch'])->name('managecr.cabinetsearch');
    Route::get('/manage/cashier/renter/{rentersid}/cabinet/add', [ManageCashierRenterController::class, 'cabinetadd'])->name('managecr.cabinetadd');
    Route::get('/manage/cashier/renter/cabinet/create', [ManageCashierRenterController::class, 'cabinetcreate'])->name('managecr.cabinetcreate');
    Route::get('/manage/cashier/renter/{rentersid}/cabinet/modify/{cabid}', [ManageCashierRenterController::class, 'cabinetmodify'])->name('managecr.cabinetmodify');
    Route::put('/manage/cashier/renter/cabinet/delete/{cabid}', [ManageCashierRenterController::class, 'cabinetdelete'])->name('managecr.cabinetdelete');
    Route::put('/manage/cashier/renter/cabinet/update/{cabid}', [ManageCashierRenterController::class, 'cabinetupdate'])->name('managecr.cabinetupdate');
    Route::post('/manage/cashier/renter/{rentersid}/cabinet/store', [ManageCashierRenterController::class, 'cabinetstore'])->name('managecr.cabinetstore');
    Route::get('/manage/cashier/renter/create/info', [ManageCashierRenterController::class, 'renterinfo'])->name('managecr.renterinfo');
    Route::get('/manage/cashier/renter/create/login', [ManageCashierRenterController::class, 'renterlogin'])->name('managecr.renterlogin');
    Route::post('/manage/cashier/renter/create/register', [ManageCashierRenterController::class, 'renterregister'])->name('managecr.renterregisternew');
    Route::post('/manage/cashier/renter/create/register/{userid}', [ManageCashierRenterController::class, 'renterregister'])->name('managecr.renterregister');
});

Route::middleware('auth')->group(function () {
    Route::get('/transaction/attendance', [TransactionAttendanceController::class, 'index'])->name('transactionattendance.index');
    Route::post('/transaction/attendance', [TransactionAttendanceController::class, 'store'])->name('transactionattendance.store');
    Route::get('/transaction/attendance/create', [TransactionAttendanceController::class, 'create'])->name('transactionattendance.create');
    Route::get('/transaction/attendance/search', [TransactionAttendanceController::class, 'search'])->name('transactionattendance.search');
    Route::get('/transaction/attendance/{attendance}', [TransactionAttendanceController::class, 'show'])->name('transactionattendance.show');
    Route::patch('/transaction/attendance/{attendance}', [TransactionAttendanceController::class, 'update'])->name('transactionattendance.update');
    Route::delete('/transaction/attendance/{attendance}', [TransactionAttendanceController::class, 'destroy'])->name('transactionattendance.destroy');
    Route::get('/transaction/attendance/{attendance}/edit', [TransactionAttendanceController::class, 'edit'])->name('transactionattendance.edit');
    Route::get('/transaction/attendance/select/employee', [TransactionAttendanceController::class, 'selectemp'])->name('transactionattendance.selectemp');
    Route::get('/transaction/attendance/select/employee/search', [TransactionAttendanceController::class, 'searchemp'])->name('transactionattendance.searchemp');
    Route::put('/transaction/attendance/select/employee/{users}', [TransactionAttendanceController::class, 'putemp'])->name('transactionattendance.putemp');

    Route::get('/transaction/branch/cabinet', [TransactionCabinetSalesController::class, 'index'])->name('transactioncabsales.index');
    Route::post('/transaction/branch/cabinet', [TransactionCabinetSalesController::class, 'store'])->name('transactioncabsales.store');
    Route::get('/transaction/branch/cabinet/create', [TransactionCabinetSalesController::class, 'create'])->name('transactioncabsales.create');
    Route::get('/transaction/branch/{branchname}/cabinet/list', [TransactionCabinetSalesController::class, 'listcabinet'])->name('transactioncabsales.listcabinet');
    Route::get('/transaction/branch/{branchname}/cabinet/list/search', [TransactionCabinetSalesController::class, 'listcabinetsearch'])->name('transactioncabsales.listcabinetsearch');
    Route::get('/transaction/branch/{branchname}/cabinet/list/sales', [TransactionCabinetSalesController::class, 'listsales'])->name('transactioncabsales.listsales');
    Route::get('/transaction/branch/{branchname}/cabinet/list/sales/search', [TransactionCabinetSalesController::class, 'listsalessearch'])->name('transactioncabsales.listsalessearch');
    Route::get('/transaction/branch/cabinet/list/sales/{salesid}/modify', [TransactionCabinetSalesController::class, 'listsalesmodify'])->name('transactioncabsales.listsalesmodify');
    Route::post('/transaction/branch/cabinet/list/sales/{salesid}/update', [TransactionCabinetSalesController::class, 'listsalesupdate'])->name('transactioncabsales.listsalesupdate');
    Route::get('/transaction/branch/cabinet/list/sales/{renter}/summary', [TransactionCabinetSalesController::class, 'cabinetsales'])->name('transactioncabsales.cabinetsales');
    Route::get('/transaction/branch/cabinet/search', [TransactionCabinetSalesController::class, 'search'])->name('transactioncabsales.search');
    Route::get('/transaction/branch/cabinet/{branch}', [TransactionCabinetSalesController::class, 'show'])->name('transactioncabsales.show');
    Route::patch('/transaction/branch/cabinet/{branch}', [TransactionCabinetSalesController::class, 'update'])->name('transactioncabsales.update');
    Route::delete('/transaction/branch/cabinet/{branch}', [TransactionCabinetSalesController::class, 'destroy'])->name('transactioncabsales.destroy');
    Route::get('/transaction/branch/cabinet/{branch}/edit', [TransactionCabinetSalesController::class, 'edit'])->name('transactioncabsales.edit');


    Route::get('/transaction/rental', [TransactionRentalController::class, 'index'])->name('transactionrental.index');
    Route::post('/transaction/rental', [TransactionRentalController::class, 'store'])->name('transactionrental.store');
    Route::get('/transaction/rental/create', [TransactionRentalController::class, 'create'])->name('transactionrental.create');
    Route::get('/transaction/rental/search', [TransactionRentalController::class, 'search'])->name('transactionrental.search');
    Route::get('/transaction/rental/{rental}', [TransactionRentalController::class, 'show'])->name('transactionrental.show');
    Route::patch('/transaction/rental/{rental}', [TransactionRentalController::class, 'update'])->name('transactionrental.update');
    Route::delete('/transaction/rental/{rental}', [TransactionRentalController::class, 'destroy'])->name('transactionrental.destroy');
    Route::get('/transaction/rental/{rental}/edit', [TransactionRentalController::class, 'edit'])->name('transactionrental.edit');
    Route::get('/transaction/rental/payments/select/renter', [TransactionRentalController::class, 'selectrenter'])->name('transactionrental.selectrenter');
    Route::get('/transaction/rental/payments/search/renter', [TransactionRentalController::class, 'searchrenter'])->name('transactionrental.searchrenter');
    Route::get('/transaction/rental/payments/select/{renters}/cabinet', [TransactionRentalController::class, 'selectcabinet'])->name('transactionrental.selectcabinet');
    Route::get('/transaction/rental/payments/select/payment', [TransactionRentalController::class, 'selectpayment'])->name('transactionrental.selectpayment');
    Route::get('/transaction/rental/payments/set/payment/month', [TransactionRentalController::class, 'setpayment'])->name('transactionrental.setpayment');
    Route::get('/transaction/rental/payments/set/payment/month/store', [TransactionRentalController::class, 'storesetpayment'])->name('transactionrental.storesetpayment');

    Route::get('/transaction/sales', [TransactionSalesController::class, 'index'])->name('transactionsales.index');
    Route::post('/transaction/sales', [TransactionSalesController::class, 'store'])->name('transactionsales.store');
    Route::get('/transaction/sales/create', [TransactionSalesController::class, 'create'])->name('transactionsales.create');
    Route::get('/transaction/sales/search', [TransactionSalesController::class, 'search'])->name('transactionsales.search');
    Route::get('/transaction/sales/{sales}', [TransactionSalesController::class, 'show'])->name('transactionsales.show');
    Route::patch('/transaction/sales/{sales}', [TransactionSalesController::class, 'update'])->name('transactionsales.update');
    Route::delete('/transaction/sales/{sales}', [TransactionSalesController::class, 'destroy'])->name('transactionsales.destroy');
    Route::get('/transaction/sales/{sales}/edit', [TransactionSalesController::class, 'edit'])->name('transactionsales.edit');

    Route::get('/transaction/eod', [TransactionEODController::class, 'index'])->name('transactioneod.index');
    Route::post('/transaction/eod', [TransactionEODController::class, 'store'])->name('transactioneod.store');
    Route::get('/transaction/eod/create', [TransactionEODController::class, 'create'])->name('transactioneod.create');
    Route::get('/transaction/eod/search', [TransactionEODController::class, 'search'])->name('transactioneod.search');
    Route::get('/transaction/eod/{eod}', [TransactionEODController::class, 'show'])->name('transactioneod.show');
    Route::patch('/transaction/eod/{eod}', [TransactionEODController::class, 'update'])->name('transactioneod.update');
    Route::delete('/transaction/eod/{eod}', [TransactionEODController::class, 'destroy'])->name('transactioneod.destroy');
    Route::get('/transaction/eod/{eod}/edit', [TransactionEODController::class, 'edit'])->name('transactioneod.edit');

});

Route::middleware('auth')->group(function () {
    Route::get('/reports/attendance', [ReportAttendanceController::class, 'index'])->name('reportsattendance.index');
    Route::get('/reports/attendance/search', [ReportAttendanceController::class, 'search'])->name('reportsattendance.search');

    Route::get('/reports/rentals', [ReportRentalsController::class, 'index'])->name('reportsrentals.index');
    Route::get('/reports/rentals/search', [ReportRentalsController::class, 'search'])->name('reportsrentals.search');

    Route::get('/reports/sales', [ReportSalesController::class, 'index'])->name('reportssales.index');
    Route::get('/reports/sales/search', [ReportSalesController::class, 'search'])->name('reportssales.search');

    Route::get('/reports/sales/top/branch', [ReportTopSalesController::class, 'index'])->name('reportstopsales.index');
    Route::get('/reports/sales/top/branch/search', [ReportTopSalesController::class, 'search'])->name('reportstopsales.search');
});


Route::middleware('auth')->group(function () {
    Route::get('saleseod', [SalesEODController::class, 'index'])->name('saleseod.index');
    Route::get('saleseod/create', [SalesEODController::class, 'create'])->name('saleseod.create');
    Route::put('saleseod', [SalesEODController::class, 'store'])->name('saleseod.store');
    Route::get('saleseod/{saleseod}/edit', [SalesEODController::class, 'edit'])->name('saleseod.edit');
    Route::put('saleseod/{saleseod}', [SalesEODController::class, 'update'])->name('saleseod.update');

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

Route::get('email', [CBMailController::class, 'index'])->name('cbmail.index');
Route::post('email/send',[CBMailController::class, 'sendmail'])->name('cbmail.sendmail');


require __DIR__.'/auth.php';
