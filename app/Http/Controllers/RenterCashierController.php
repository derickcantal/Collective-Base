<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Renters;
use App\Models\branch;
use App\Models\cabinet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;
use App\Http\Requests\RenterCreateRequests;
use App\Http\Requests\RenterSearchRequests;
use App\Http\Requests\RenterUpdateRequests;

class RenterCashierController extends Controller
{
    public function cabinetsearch(Request $request)
    {
        dd('Search Cabinet');
    }
    public function cabinetadd(Request $request)
    {
        dd('Assign Cabinet');
    }
    public function search(Request $request)
    {
        dd($request->pagerow);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $renter = Renters::where('branchname', auth()->user()->branchname)
                        ->where(function(Builder $builder){
                        $builder->where('accesstype',"Renters")
                                ->orderBy('status','asc');
                            })->paginate(5);

        return view('rentercashier.index',compact('renter'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rentercashier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        return redirect()->route('renter.index')
         ->with('success','Renter created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Renters $renter)
    {
        $cabinets = cabinet::where('userid',$renter->userid)
        ->where(function(Builder $builder){
            $builder->where('branchname', auth()->user()->branchname)
                    ->orderBy('status','asc')
                    ->orderBy('branchname','asc');
        })
                    ->paginate(5);
        return view('rentercashier.show',['renter' => $renter])
            ->with(compact('cabinets'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Renters $renter)
    {

        return view('rentercashier.edit',['renter' => $renter]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return redirect()->route('renter.index')
         ->with('success','Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        dd('Deactivate Renter');
    }
}
