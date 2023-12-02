<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Models\Sales;

class DashboardController extends Controller
{
    public function displaysales()
    {
        $sales = Sales::all();
        
        return view('dashboard.edit',['sales' => $sales]);
    }

}
