<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Models\history_sales;
use App\Models\history_sales_requests;
use App\Models\history_attendance;
use App\Models\history_rental_payments;
use \Carbon\Carbon;

class ReportsController extends Controller
{
    public function displayall()
    {  
        $sales = history_sales::latest()->paginate(5);
        $sales_requests = history_sales_requests::where('status','Pending')->orderBy('status','desc')->paginate(5);
        
        $rentalpayments = history_rental_payments::where('status','Unpaid')->orderBy('status','desc')->paginate(5);

        $attendance = history_attendance::paginate(5); 

        return view('reports.index')->with(['sales' => $sales])
                                    ->with(['sales_requests' => $sales_requests])
                                    ->with(['attendance' => $attendance])
                                    ->with(['rentalpayments' => $rentalpayments]);
    }
}
