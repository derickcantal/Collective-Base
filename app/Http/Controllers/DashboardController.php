<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Models\Sales;
use App\Models\Sales_Requests;

class DashboardController extends Controller
{
    public function displayall()
    {
        $sales = sales::all();
        $sales_requests = DB::table('sales_requests')->get();

        return view('dashboard.edit',['sales' => $sales],['sales_requests' => $sales_requests]);
    }
}
