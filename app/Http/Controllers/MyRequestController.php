<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RenterRequests;
use App\Models\Sales;
use App\Models\Renters;
use App\Models\branch;
use App\Models\cabinet;
use App\Models\history_sales;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;

class MyRequestController extends Controller
{
    public function loaddata(){
        $cabinets = cabinet::where('userid',auth()->user()->userid)
                    ->orderBy('status','asc')
                    ->orderBy('cabid','asc')
                    ->orderBy('branchname','asc')
                    ->paginate(5);

        return view('myrequest.index',['cabinets' => $cabinets])
                    ->with('i', (request()->input('page', 1) - 1) * 5);

        $RenterRequests = RenterRequests::where('cabinetname',auth()->user()->cabinetname)
                    ->where(function(Builder $builder){
                        $builder->where('branchname',auth()->user()->branchname);
                                
                    })->paginate(5);

            return view('myrequest.index',compact('RenterRequests'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function sales($cabid){
        $history_sales = history_sales::where('cabid',$cabid)
                    ->where(function(Builder $builder){
                        $builder->where('collected_status', "For Approval");
                    })->paginate(5);

        $history_sales1 = history_sales::where('cabid',$cabid)
                    ->where(function(Builder $builder){
                        $builder->where('collected_status', "For Approval");
                    })->get();
                   
        $totalsales = collect($history_sales1)->sum('total');

        if($totalsales == 0)
        {
            return redirect()->route('myrequest.index')
                                ->with('failed','No Records Found.');
        }

        return view('myrequest.sales',compact('history_sales'))
                ->with('cabid',$cabid)
                ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function storedata($request,$cabid){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');

        $cabinet = cabinet::where('cabid',$cabid)
        ->where(function(Builder $builder){
            $builder->where('userid', auth()->user()->userid);
        })->first();

        $renter = Renters::where('userid',$cabinet->userid)->first();

        $history_sales = history_sales::where('cabid',$cabid)
                ->where(function(Builder $builder){
                    $builder->where('collected_status', "Pending");
                })->get();

        $totalsales = collect($history_sales)->sum('total');
        
        if(empty($request->rnotes))
        {
            $rnotes = 'Null';
        }
        else
        {
            $rnotes =  $request->rnotes;
        }

        $RenterRequests = RenterRequests::create([
            'branchid' => $cabinet->branchid,
            'branchname' => $cabinet->branchname,
            'cabid' => $cabinet->cabid,
            'cabinetname' => $cabinet->cabinetname,
            'totalsales' => $totalsales,
            'totalcollected' => 0,
            'avatarproof' => 'avatars/cash-default.jpg',
            'rnotes' => $rnotes,
            'userid' => $renter->userid,
            'firstname' => $renter->firstname,
            'lastname' => $renter->lastname,
            'created_by' => Auth()->user()->email,
            'updated_by' => 'Null',
            'timerecorded' => $timenow,
            'timerecorded_c' => 'Null',
            'mod' => 0,
            'posted' => 'N',
            'status' => 'For Approval',
        ]);

        history_sales::where('cabid',$cabid)
                    ->where(function(Builder $builder){
                        $builder->where('collected_status', "Pending")
                                ->where('total','!=', 0)
                                ->where('returned', 'N');
                    })->update([
                        'collected_status' => 'For Approval',
                        'updated_by' => auth()->user()->email,
                    ]);
    
        if ($RenterRequests) {
            //query successful
            return redirect()->route('myrequest.index')
                        ->with('success','Sales Request created successfully.');
        }else{
            return redirect()->route('myrequest.index')
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
    public function create($cabid)
    {

        $cabinet = cabinet::where('cabid',$cabid)
                    ->where(function(Builder $builder){
                        $builder->where('userid', auth()->user()->userid);
                    })->first();
        
        $renter = Renters::where('userid',$cabinet->userid)->first();

        

        $history_sales = history_sales::where('cabid',$cabid)
                    ->where(function(Builder $builder){
                        $builder->where('collected_status', "Pending");
                    })->get();


                   
        $totalsales = collect($history_sales)->sum('total');


        return view('myrequest.create')
                    ->with(['cabinet'=>$cabinet])
                    ->with(['renter'=>$renter])
                    ->with(['history_sales'=>$history_sales])
                    ->with('totalsales',$totalsales)
                    ->with('cabid',$cabid);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $cabid)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return $this->storedata($request, $cabid);
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
     * Display the specified resource.
     */
    public function show(string $cabid)
    {
        
        $history_sales = history_sales::where('cabid',$cabid)
                    ->where(function(Builder $builder){
                        $builder->where('collected_status', "Pending");
                    })->paginate(5);

        $history_sales1 = history_sales::where('cabid',$cabid)
                    ->where(function(Builder $builder){
                        $builder->where('collected_status', "Pending");
                    })->get();
                   
        $totalsales = collect($history_sales1)->sum('total');

        if($totalsales == 0)
        {
            return redirect()->route('myrequest.index')
                                ->with('failed','No Sales to collect.');
        }

        return view('myrequest.show')
                    ->with('history_sales',$history_sales)
                    ->with('totalsales',$totalsales)
                    ->with('cabid',$cabid)
                    ->with('i', (request()->input('page', 1) - 1) * 5);
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
