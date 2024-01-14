<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Models\history_Sales;
use App\Models\history_Sales_Requests;
use App\Models\history_attendance;
use App\Models\history_Rental_Payment;
use \Carbon\Carbon;

class ReportsController extends Controller
{
    public function displayall()
    {
        $sales = DB::table('history_sales')->latest()->get();
        $sales_requests = DB::table('history_sales_requests')->get();
        $attendance = DB::table('history_attendance')->get();
        $rentalpayments = DB::table('history_rental_payments')->get();

        return view('reports.index')->with(['sales' => $sales])
                                    ->with(['sales_requests' => $sales_requests])
                                    ->with(['attendance' => $attendance])
                                    ->with(['rentalpayments' => $rentalpayments]);
    }
}
