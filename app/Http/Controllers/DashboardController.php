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
        $sales = Sales::paginate(5);
        $RenterRequests = RenterRequests::where('status','Pending')->orderBy('status','desc')->paginate(5);
        
        $rentalpayments = RentalPayments::where('status','Unpaid')->orderBy('status','desc')->paginate(5);

        $attendance = attendance::paginate(5);

        return view('dashboard.index')->with(['sales' => $sales])
                                        ->with(['RenterRequests' => $RenterRequests])
                                        ->with(['attendance' => $attendance])
                                        ->with(['rentalpayments' => $rentalpayments])
                                        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function renters(){
        $sales = Sales::where('cabinetname',auth()->user()->cabinetname)
                    ->where(function(Builder $builder){
                        $builder->where('branchname',auth()->user()->branchname)
                                ->where('collected_status','Pending');
                    })->paginate(5);
        $RenterRequests = RenterRequests::where('cabinetname',auth()->user()->cabinetname)
                    ->where(function(Builder $builder){
                        $builder->where('branchname',auth()->user()->branchname)
                                ->orderBy('status','desc');
                    })->paginate(5);

        $rentalpayments = RentalPayments::where('cabinetname',auth()->user()->cabinetname)
                    ->where(function(Builder $builder){
                        $builder->where('branchname',auth()->user()->branchname)
                                ->where('status','Pending');
                    })->paginate(5);

        $attendance = attendance::where('status','Renters')->paginate(5);

        return view('dashboard.index')->with(['sales' => $sales])
                                        ->with(['RenterRequests' => $RenterRequests])
                                        ->with(['rentalpayments' => $rentalpayments])
                                        ->with(['attendance' => $attendance])
                                        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function cashier(){
        $sales = Sales::where('branchname',auth()->user()->branchname)
                    ->paginate(5);
        $RenterRequests = RenterRequests::where('branchname',auth()->user()->branchname)
                    ->paginate(5);

        $rentalpayments = rentalpayments::where('branchname',auth()->user()->branchname)
                    ->where(function(Builder $builder){
                        $builder->where('status','Pending');
                    })->paginate(5);

        $attendance = attendance::where('branchname',auth()->user()->branchname)->paginate(5);

        return view('dashboard.index')->with(['sales' => $sales])
                    ->with(['RenterRequests' => $RenterRequests])
                    ->with(['rentalpayments' => $rentalpayments])
                    ->with(['attendance' => $attendance])
                    ->with('i', (request()->input('page', 1) - 1) * 5);             
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
