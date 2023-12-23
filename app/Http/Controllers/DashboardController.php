<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\attendance;
use App\Models\RentalPayments;
use App\Models\RenterRequests;
use Illuminate\Contracts\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    public function displayall()
    {
        $sales = sales::get()->toQuery()->paginate(5);
        $RenterRequests = RenterRequests::where('status','Pending')->orderBy('status','desc')->paginate(5);
        $attendance = attendance::get()->toQuery()->paginate(5);
        $rentalpayments = RentalPayments::get()->toQuery()->paginate(5);

        return view('dashboard.index')->with(['sales' => $sales])
                                        ->with(['RenterRequests' => $RenterRequests])
                                        ->with(['attendance' => $attendance])
                                        ->with(['rentalpayments' => $rentalpayments])
                                        ->with('i', (request()->input('page', 1) - 1) * 5);
    }
}
