<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\attendance;
use App\Models\sales_eod;
use App\Models\RentalPayments;
use App\Models\RenterRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;
use App\Http\Controllers\SalesEODController;

class SalesEODController extends Controller
{
    public function loaddata(){
        
        
        return view('saleseod.index')
        ;
    }
    
    public function storedata($request)
    {
        
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');
        
        try{
            Sales::where('branchname',auth()->user()->branchname)
            ->where(function(Builder $builder){
                $builder->where('posted', "N");
            })->update([
                'posted' => "Y",
                'status' => "Posted",
            ]);

            attendance::where('branchname',auth()->user()->branchname)
            ->where(function(Builder $builder){
                $builder->where('posted', "N");
            })->update([
                'posted' => "Y",
                'status' => "Posted",
            ]);
        
            RenterRequests::where('branchname',auth()->user()->branchname)
            ->where(function(Builder $builder){
                $builder->where('posted', "N");
            })->update([
                'posted' => "Y",
            ]);

            RentalPayments::where('branchname',auth()->user()->branchname)
            ->where(function(Builder $builder){
                $builder->where('posted', "N");
            })->update([
                'posted' => "Y",
            ]);
            

            Sales::query()
            ->where('branchname',auth()->user()->branchname)
            ->where(function(Builder $builder){
                $builder->where('posted', "Y");
            })
            ->each(function ($oldRecord) {
                $newRecord = $oldRecord->replicate();
                $newRecord->setTable('history_sales');
                $newRecord->save();
                $oldRecord->delete();
            });

            attendance::query()
            ->where('branchname',auth()->user()->branchname)
            ->where(function(Builder $builder){
                $builder->where('posted', "Y");
            })
            ->each(function ($oldRecord) {
                $newRecord = $oldRecord->replicate();
                $newRecord->setTable('history_attendance');
                $newRecord->save();
                $oldRecord->delete();
            });

            RentalPayments::query()
            ->where('branchname',auth()->user()->branchname)
            ->where(function(Builder $builder){
                $builder->where('posted', "Y");
            })
            ->each(function ($oldRecord) {
                $newRecord = $oldRecord->replicate();
                $newRecord->setTable('history_rental_payments');
                $newRecord->save();
                $oldRecord->delete();
            });

            RenterRequests::query()
            ->where('branchname',auth()->user()->branchname)
            ->where(function(Builder $builder){
                $builder->where('posted', "Y");
            })
            ->each(function ($oldRecord) {
                $newRecord = $oldRecord->replicate();
                $newRecord->setTable('history_sales_requests');
                $newRecord->save();
                $oldRecord->delete();
            });
           
            if($request->filled('notes')){
                $saleseod = sales_eod::create([
                    'branchid' => auth()->user()->branchid,
                    'branchname' => auth()->user()->branchname,
                    'totalsales' => $request->totalsales,
                    'rentalpayments' => $request->rentalpayments,
                    'requestpayments' => $request->requestpayments,
                    'otherexpenses' => $request->otherexpenses,
                    'totalcash' => $request->totalcash,
                    'notes' => $request->notes,
                    'created_by' => auth()->user()->email,
                    'updated_by' => 'Null',
                    'timerecorded' => $timenow,
                    'posted' => 'N',
                ]); 
                if($saleseod){
                    
                    return redirect()->route('dashboard.index')
                                    ->with('success','EOD Succesful');
                }else{
                   
                    return redirect()->route('dashboard.index')
                                    ->with('failed','EOD Error.');
                }
                
            }else{
                $saleseod = sales_eod::create([
                    'branchid' => auth()->user()->branchid,
                    'branchname' => auth()->user()->branchname,
                    'totalsales' => $request->totalsales,
                    'rentalpayments' => $request->rentalpayments,
                    'requestpayments' => $request->requestpayments,
                    'otherexpenses' => $request->otherexpenses,
                    'totalcash' => $request->totalcash,
                    'notes' => 'Null',
                    'created_by' => auth()->user()->email,
                    'updated_by' => 'Null',
                    'timerecorded' => $timenow,
                    'posted' => 'N',
                ]); 
                if($saleseod){
            
                    return redirect()->route('dashboard.index')
                                    ->with('success','EOD Succesful');
                }else{
                   
                    return redirect()->route('dashboard.index')
                                    ->with('failed','EOD Error.');
                }
                
            } 
        } catch(Exception $e) {
            dd($e);
        }
        
        if($saleseod){
            
            return redirect()->route('dashboard.index')
                            ->with('success','EOD Succesful');
        }else{
           
            return redirect()->route('dashboard.index')
                            ->with('failed','EOD Error.');
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
                return $this->loaddata();  
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->loaddata();
            }
        }else{
            return redirect()->route('dashboard.index');
        }
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        
        if(Hash::check($request->password, auth()->user()->password)){
            $sales = Sales::where('branchname',auth()->user()->branchname)
            ->where(function(Builder $builder){
                $builder->where('posted', "N");
            })->get();
           
            $totalsales = collect($sales)->sum('total');

            $totalitem = collect($sales)->sum('qty');
           
            $RentalPayments = RentalPayments::where('branchname',auth()->user()->branchname)
                        ->where(function(Builder $builder){
                            $builder->where('posted', "N");
                        })->get();

            $totalrentpay = collect($RentalPayments)->sum('rpamount');

            $RenterRequests = RenterRequests::where('branchname',auth()->user()->branchname)
                        ->where(function(Builder $builder){
                            $builder->where('posted', "N");
                        })->get();            

            $totalrequests = collect($RenterRequests)->sum('totalcollected');
            return view('saleseod.create')
                ->with('totalsales',$totalsales)
                ->with('totalitem',$totalitem)
                ->with('totalrentpay',$totalrentpay)
                ->with('totalrequests',$totalrequests);
        }else{
            dd('show here');
            return redirect()->route('saleseod.index')->with('failed','Incorrect Password.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                $this->storedata($request);
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                $this->storedata($request);
            }elseif(auth()->user()->accesstype =='Administrator'){
                $this->storedata($request);
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        dd('show here');
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        dd('edit here');
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Administrator'){
                return redirect()->route('dashboard.index');
            }
        }else{
            return redirect()->route('dashboard.index');;
        }
    }
}
