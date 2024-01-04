<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentalPayments;
use Illuminate\Contracts\Database\Eloquent\Builder;

class MyRentalController extends Controller
{
    public function loaddata(){
        $RentalPayments = RentalPayments::where('cabinetname','==',"{auth()->user()->cabinetname}")
                    ->where(function(Builder $builder){
                        $builder->where('branchname',auth()->user()->branchname);
                                
                    })->paginate(5);

            return view('myrental.index',compact('RentalPayments'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function storedata(){
    
    }
    
    public function updatedata(){
    
    }
    
    public function destroydata(){
    
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return view('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return $this->loaddata();
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return view('dashboard.index');
            }elseif(auth()->user()->accesstype =='Administrator'){
                return view('dashboard.index');
            }
        }else{
            return view('dashboard.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(string $id)
    {
        //
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
