<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentalPayments;
use App\Models\cabinet;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Models\history_rental_payments;
use \Carbon\Carbon;

class MyRentalController extends Controller
{
    public function loaddata(){
        $cabinets = cabinet::where('userid',auth()->user()->userid)
                    ->orderBy('status','asc')
                    ->orderBy('cabid','asc')
                    ->orderBy('branchname','asc')
                    ->paginate(5);

        return view('myrental.index',['cabinets' => $cabinets])
                    ->with('i', (request()->input('page', 1) - 1) * 5);
        
        $RentalPayments = RentalPayments::where('userid',auth()->user()->userid)
                    ->where(function(Builder $builder){
                        $builder->where('branchid',auth()->user()->branchid);
                    })->paginate(5);
        
        $RentalPaymentsHistory = history_rental_payments::where('userid',auth()->user()->userid)
                    ->where(function(Builder $builder){
                        $builder->where('branchid',auth()->user()->branchid);
                    })->paginate(5);

        if(!empty($RentalPayments))
        {
            return view('myrental.index',['RentalPayments' => $RentalPayments])
                ->with('i', (request()->input('page', 1) - 1) * 5);
        }
        elseif(!empty($RentalPaymentsHistory))
        {
            return view('myrental.index',['RentalPayments' => $RentalPaymentsHistory])
                ->with('i', (request()->input('page', 1) - 1) * 5);
        }
        else
        {
            return redirect()->back()
                                ->with('failed','No Record Found.');
        }
            
    }
    
    public function storedata(){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');
    
    }
    
    public function updatedata(){
    
    }
    
    public function destroydata(){
    
    }

    public function cabinetrental(){
        $RentalPaymentsHistory = history_rental_payments::where('userid',auth()->user()->userid)
                    ->where(function(Builder $builder){
                        $builder->where('branchid',auth()->user()->branchid);
                    })->paginate(5);

        if(!empty($RentalPaymentsHistory))
        {
            return view('myrental.index',['RentalPayments' => $RentalPaymentsHistory])
                ->with('i', (request()->input('page', 1) - 1) * 5);
        }
        else
        {
            return redirect()->back()
                                ->with('failed','No Record Found.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return $this->loaddata();
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Administrator'){
                return redirect()->route('dashboard.index');
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('myrental.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $cabinet)
    {
        $cabinet = cabinet::where('cabid',$cabinet)->first();

        $RentalPayments = RentalPayments::where('cabid',$cabinet)->latest()->paginate(5);
        
        $RentalPaymentsHistory = history_rental_payments::where('cabid',$cabinet)->latest()->paginate(5);

        $RentalPayments1 = RentalPayments::where('cabid',$cabinet)->latest()->get();
        
        $RentalPaymentsHistory1 = history_rental_payments::where('cabid',$cabinet)->latest()->get();

        $totalsales = collect($RentalPayments1)->sum('total');

        $totalsales1 = collect($RentalPaymentsHistory1)->sum('total');

        if(!empty($RentalPayments))
        {
            if($totalsales == 0){
                return redirect()->back()
                                ->with('failed','No Record Found.');
            }
            return view('myrental.show',['rentalpayments' => $RentalPayments])
                ->with(['cabid'=>$cabinet->cabinetname])
                ->with('i', (request()->input('page', 1) - 1) * 5);
        }
        elseif(!empty($RentalPaymentsHistory))
        {
            if($totalsales1 == 0){
                return redirect()->back()
                                ->with('failed','No Record Found.');
            }
            return view('myrental.show',['rentalpayments' => $RentalPaymentsHistory])
                ->with(['cabid'=>$cabinet->cabinetname])
                ->with('i', (request()->input('page', 1) - 1) * 5);
        }
        else
        {
            return redirect()->back()
                                ->with('failed','No Record Found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
