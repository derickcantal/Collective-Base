<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CabinetController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RentersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\Reports\ReportAttendanceController;
use App\Http\Controllers\Reports\ReportRentalsController;
use App\Http\Controllers\Reports\ReportRequestsController;
use App\Http\Controllers\Reports\ReportSalesController;
use App\Http\Controllers\Reports\ReportTopSalesController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Counter;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Manage\ManageBranchController;
use App\Http\Controllers\Manage\ManageCabinetController;
use App\Http\Controllers\Manage\ManageRenterController;
use App\Http\Controllers\Manage\ManageUserController;
use App\Http\Controllers\Manage\ManageMailboxController;
use App\Http\Controllers\Transaction\TransactionAttendanceController;
use App\Http\Controllers\Transaction\TransactionRentalController;
use App\Http\Controllers\Transaction\TransactionRequestController;
use App\Http\Controllers\Transaction\TransactionSalesController;
use App\Http\Controllers\Transaction\TransactionEODController;
use App\Http\Controllers\Dashboard\DashboardOverviewController;
use App\Http\Controllers\Dashboard\DashboardRentalsController;
use App\Http\Controllers\Dashboard\DashboardRequestsController;
use App\Http\Controllers\Dashboard\DashboardSalesController;
use App\Http\Controllers\Dashboard\DashboardAttendanceController;
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
use App\Http\Controllers\CBMailController;
use App\Http\Controllers\MailboxController;


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

    Route::get('/dashboard/overview', [DashboardOverviewController::class, 'index'])->name('dashboardoverview.index');

    Route::get('/dashboard/rentals', [DashboardRentalsController::class, 'index'])->name('dashboardrentals.index');

    Route::get('/dashboard/requests', [DashboardRequestsController::class, 'index'])->name('dashboardrequests.index');

    Route::get('/dashboard/sales', [DashboardSalesController::class, 'index'])->name('dashboardsales.index');

    Route::get('/dashboard/attendance', [DashboardAttendanceController::class, 'index'])->name('dashboardattendance.index');

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

    Route::get('/manage/renters', [ManageRenterController::class, 'index'])->name('managerenter.index');
    Route::post('/manage/renters', [ManageRenterController::class, 'store'])->name('managerenter.store');
    Route::get('/manage/renters/create', [ManageRenterController::class, 'create'])->name('managerenter.create');
    Route::get('/manage/renters/search', [ManageRenterController::class, 'search'])->name('managerenter.search');
    Route::get('/manage/renters/{renters}', [ManageRenterController::class, 'show'])->name('managerenter.show');
    Route::patch('/manage/renters/{renters}', [ManageRenterController::class, 'update'])->name('managerenter.update');
    Route::delete('/manage/renters/{renters}', [ManageRenterController::class, 'destroy'])->name('managerenter.destroy');
    Route::get('/manage/renters/{renters}/edit', [ManageRenterController::class, 'edit'])->name('managerenter.edit');
    Route::get('/manage/renters/selectbranch', [ManageRenterController::class, 'selectbranch'])->name('managerenter.selectbranch');
    Route::put('/manage/renters/createrenter/{branchid}', [ManageRenterController::class, 'createrenter'])->name('managerenter.createrenter');
    Route::put('/manage/renters/cabinet/modify/{cabinetid}', [ManageRenterController::class, 'updatecabinet'])->name('managerenter.updatecabinet');
    Route::get('/manage/renters/cabinet/{cabinetid}', [ManageRenterController::class, 'editcabinet'])->name('managerenter.editcabinet');
    Route::get('/manage/renters/cabinet/status/{cabinetid}', [ManageRenterController::class, 'statuscabinet'])->name('managerenter.statuscabinet');
    Route::get('/manage/renters/create/info', [ManageRenterController::class, 'renterinfo'])->name('managerenter.renterinfo');
    Route::get('/manage/renters/create/login', [ManageRenterController::class, 'renterlogin'])->name('managerenter.renterlogin');
    Route::put('/manage/renters/create/register/{renter}', [ManageRenterController::class, 'renterregister'])->name('managerenter.renterregister');

    Route::get('/manage/cabinet', [ManageCabinetController::class, 'index'])->name('managecabinet.index');
    Route::post('/manage/cabinet', [ManageCabinetController::class, 'store'])->name('managecabinet.store');
    Route::get('/manage/cabinet/create', [ManageCabinetController::class, 'create'])->name('managecabinet.create');
    Route::get('/manage/cabinet/search', [ManageCabinetController::class, 'search'])->name('managecabinet.search');
    Route::get('/manage/cabinet/{cabinet}', [ManageCabinetController::class, 'show'])->name('managecabinet.show');
    Route::patch('/manage/cabinet/{cabinet}', [ManageCabinetController::class, 'update'])->name('managecabinet.update');
    Route::delete('/manage/cabinet/{cabinet}', [ManageCabinetController::class, 'destroy'])->name('managecabinet.destroy');
    Route::get('/manage/cabinet/{cabinet}/edit', [ManageCabinetController::class, 'edit'])->name('managecabinet.edit');

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

    Route::get('/transaction/request', [TransactionRequestController::class, 'index'])->name('transactionrequest.index');
    Route::post('/transaction/request', [TransactionRequestController::class, 'store'])->name('transactionrequest.store');
    Route::get('/transaction/request/create', [TransactionRequestController::class, 'create'])->name('transactionrequest.create');
    Route::get('/transaction/request/search', [TransactionRequestController::class, 'search'])->name('transactionrequest.search');
    Route::get('/transaction/request/{request}', [TransactionRequestController::class, 'show'])->name('transactionrequest.show');
    Route::patch('/transaction/request/{request}', [TransactionRequestController::class, 'update'])->name('transactionrequest.update');
    Route::delete('/transaction/request/{request}', [TransactionRequestController::class, 'destroy'])->name('transactionrequest.destroy');
    Route::get('/transaction/request/{request}/edit', [TransactionRequestController::class, 'edit'])->name('transactionrequest.edit');

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

    Route::get('/reports/requests', [ReportRequestsController::class, 'index'])->name('reportsrequests.index');
    Route::get('/reports/requests/search', [ReportRequestsController::class, 'search'])->name('reportsrequests.search');

    Route::get('/reports/sales', [ReportSalesController::class, 'index'])->name('reportssales.index');
    Route::get('/reports/sales/search', [ReportSalesController::class, 'search'])->name('reportssales.search');

    Route::get('/reports/sales/top/branch', [ReportTopSalesController::class, 'index'])->name('reportstopsales.index');
    Route::get('/reports/sales/top/branch/search', [ReportTopSalesController::class, 'search'])->name('reportstopsales.search');
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
    Route::get('myrequest/select/date/range/{cabinet}/payments', [MyRequestController::class, 'create'])->name('myrequest.creates');
    Route::get('myrequest/select/date/range/{cabinet}', [MyRequestController::class, 'create_select_range'])->name('myrequest.create_select_range');
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

    Route::get('mailbox/search', [MailboxController::class, 'search'])->name('mailbox.search');
    Route::get('mailbox', [MailboxController::class, 'index'])->name('mailbox.index');
    Route::get('mailbox/create', [MailboxController::class, 'create'])->name('mailbox.create');
    Route::post('mailbox/store', [MailboxController::class, 'store'])->name('mailbox.store');
    Route::get('mailbox/{mailbox}/edit', [MailboxController::class, 'edit'])->name('mailbox.edit');
    Route::put('mailbox/{mailbox}', [MailboxController::class, 'update'])->name('mailbox.update');
    Route::delete('mailbox/{mailbox}/destroy', [MailboxController::class, 'destroy'])->name('mailbox.destroy');
    Route::get('mailbox/{mailbox}/history', [MailboxController::class, 'show'])->name('mailbox.show');

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


Route::get('email', [CBMailController::class, 'index'])->name('cbmail.index');
Route::post('email/send',[CBMailController::class, 'sendmail'])->name('cbmail.sendmail');


require __DIR__.'/auth.php';
