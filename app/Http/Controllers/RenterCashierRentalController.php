<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;
use App\Models\cabinet;
use App\Models\branch;
use App\Models\Renters;
use App\Models\history_rental_payments;
use App\Models\RentalPayments;
use App\Models\rental_active_month;

class RenterCashierRentalController extends Controller
{
    public function search(Request $request)
    {
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cabinets = cabinet::where('branchname',auth()->user()->branchname)
                ->where(function(Builder $builder){
                    $builder->where('email','!=','Vacant')
                        ->orderBy('status','asc')
                        ->orderBy('cabid','asc')
                        ->orderBy('branchname','asc');
                    })
                    
                    ->paginate(10);
    
        return view('rentercashierrental.index',compact('cabinets'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($cabid)
    {
        $cabinet = cabinet::where('cabid', $cabid)->first();

        $renter = Renters::where('userid', $cabinet->userid)->first();

        $rentername = $renter->lastname . ', ' . $renter->firstname;


        return view('rentercashierrental.create')
                                ->with(['cabinet' => $cabinet])
                                ->with(['renters' => $renter])
                                ->with('rentername', $rentername);
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
        return view('rentercashierrental.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
        $currentmonth = rental_active_month::query()->get();


        if(empty($currentmonth))
        {
            return redirect()->route('rentercashierrental.index')
                                ->with('failed','No Records Found.');
        }
        else{
            dd('Record Found');
                
        }
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
