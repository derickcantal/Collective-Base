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
