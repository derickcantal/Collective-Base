<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Models\Sales;
use App\Models\Sales_Requests;

class SalesController extends Controller
{
    public function displayall()
    {
        $sales = sales::all();

        return view('dashboard.edit',['sales' => $sales]);
    }
}
