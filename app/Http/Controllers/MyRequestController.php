<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RenterRequests;
use App\Models\Sales;
use App\Models\Renters;
use App\Models\branch;
use App\Models\cabinet;
use Illuminate\Contracts\Database\Eloquent\Builder;

class MyRequestController extends Controller
{
    public function loaddata(){
        $RenterRequests = RenterRequests::where('cabinetname',auth()->user()->cabinetname)
                    ->where(function(Builder $builder){
                        $builder->where('branchname',auth()->user()->branchname);
                                
                    })->paginate(5);

            return view('myrequest.index',compact('RenterRequests'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function storedata($request){
        $rent = Renters::where('username',auth()->user()->username)->first();

        $br = branch::where('branchname',auth()->user()->branchname)->first();

        $cab = cabinet::where('cabinetname',auth()->user()->cabinetname)
        ->where(function(Builder $builder) use($request){
            $builder->where('branchname',auth()->user()->branchname);
        })->first();

        $RenterRequests = RenterRequests::create([
            'branchid' => $br->branchid,
            'branchname' => $request->branchname,
            'cabid' => $cab->cabid,
            'cabinetname' => $request->cabinetname,
            'totalsales' => $request->totalsales,
            'totalcollected' => $request->totalcollected,
            'avatarproof' => 'avatars/cash-default.jpg',
            'rnotes' => $request->rnotes,
            'userid' => $rent->userid,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'created_by' => Auth()->user()->email,
            'updated_by' => 'Null',
            'mod' => 0,
            'posted' => 'N',
            'status' => 'Pending',
        ]);
    
        if ($RenterRequests) {
            //query successful
            return redirect()->route('myrequest.create')
                        ->with('success','Sales Request created successfully.');
        }else{
            return redirect()->route('myrequest.create')
                        ->with('success','Sales Request creation failed');
        }
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
        $sales = Sales::where('branchname',auth()->user()->branchname)
                    ->where(function(Builder $builder){
                        $builder->where('cabinetname', auth()->user()->cabinetname)
                                ->where('collected_status', "Pending");
                    })->get();
                   
        $totalsales = collect($sales)->sum('total');
        return view('myrequest.create')->with('totalsales',$totalsales);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return view('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return $this->storedata($request);
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
        $RenterRequests = RenterRequests::findOrFail($id);
        return view('myrequest.edit',['RenterRequests' => $RenterRequests]);
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
