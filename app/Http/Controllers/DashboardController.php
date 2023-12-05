<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Models\Sales;
use App\Models\Sales_Requests;
use App\Models\attendance;
use App\Models\RentalPayment;

class DashboardController extends Controller
{
    public function displayall()
    {
        $sales = sales::all();
        $sales_requests = DB::table('sales_requests')->get();
        $attendance = DB::table('attendance')->get();
        $rentalpayments = DB::table('rental_payments')->get();

        return view('dashboard.edit')->with(['sales' => $sales])
                                        ->with(['sales_requests' => $sales_requests])
                                        ->with(['attendance' => $attendance])
                                        ->with(['rentalpayments' => $rentalpayments]);
    }
}
