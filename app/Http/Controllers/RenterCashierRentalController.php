<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;
use App\Models\cabinet;
use App\Models\branch;
use App\Models\Renters;

class RenterCashierRentalController extends Controller
{
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
