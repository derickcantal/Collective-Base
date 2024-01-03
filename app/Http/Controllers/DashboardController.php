<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\attendance;
use App\Models\RentalPayments;
use App\Models\RenterRequests;
use Illuminate\Contracts\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    public function administrator(){
        $sales = sales::get()->toQuery()->paginate(5);
        $RenterRequests = RenterRequests::where('status','Pending')->orderBy('status','desc')->paginate(5);
        $attendance = attendance::get()->toQuery()->paginate(5);
        $rentalpayments = RentalPayments::where('status','Unpaid')->orderBy('status','desc')->paginate(5);

        return view('dashboard.index')->with(['sales' => $sales])
                                        ->with(['RenterRequests' => $RenterRequests])
                                        ->with(['attendance' => $attendance])
                                        ->with(['rentalpayments' => $rentalpayments])
                                        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function renters(){

    }

    public function cashier(){

    }
    public function displayall()
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return $this->cashier();
            }elseif(auth()->user()->accesstype =='Renters'){
                return $this->renters();
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->administrator();
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->administrator();
            }
        }else{
            return view('welcome');
        }
        
    }
}
