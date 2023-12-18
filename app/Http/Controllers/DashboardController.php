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
use App\Models\RentalPayments;
use App\Models\SalesRequests;
use Illuminate\Contracts\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    public function displayall()
    {
        $sales = sales::get()->toQuery()->paginate(5);
        $sales_requests = SalesRequests::get()->toQuery()->paginate(5);
        $attendance = attendance::get()->toQuery()->paginate(5);
        $rentalpayments = RentalPayments::get()->toQuery()->paginate(5);

        return view('dashboard.index')->with(['sales' => $sales])
                                        ->with(['sales_requests' => $sales_requests])
                                        ->with(['attendance' => $attendance])
                                        ->with(['rentalpayments' => $rentalpayments])
                                        ->with('i', (request()->input('page', 1) - 1) * 5);
    }
}
