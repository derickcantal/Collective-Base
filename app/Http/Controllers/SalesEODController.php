<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\attendance;
use App\Models\sales_eod;
use App\Models\RentalPayments;
use App\Models\RenterRequests;
use App\Models\user_login_log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;

class SalesEODController extends Controller
{
    public function userlog($notes,$status){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        
        $userlog = user_login_log::query()->create([
            'userid' => auth()->user()->userid,
            'username' => auth()->user()->username,
            'firstname' => auth()->user()->firstname,
            'middlename' => auth()->user()->middlename,
            'lastname' => auth()->user()->lastname,
            'email' => auth()->user()->email,
            'branchid' => auth()->user()->branchid,
            'branchname' => auth()->user()->branchname,
            'accesstype' => auth()->user()->accesstype,
            'timerecorded'  => $timenow,
            'created_by' => auth()->user()->email,
            'updated_by' => 'Null',
            'mod'  => 0,
            'notes' => $notes,
            'status'  => $status,
        ]);
    }
    public function loaddata()
    {
        $notes = 'Sales. EOD.';
        $status = 'Success';
        $this->userlog($notes,$status);

        return view('saleseod.index');
    }
    
    public function storedata($request)
    {
        
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        
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
                $builder->where('posted', "N")
                    ->where('fully_paid', 'Y');
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
                $builder->where('posted', "Y")
                        ->where('fully_paid', 'Y');
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
                    $notes = 'Sales. EOD. Create.';
                    $status = 'Success';
                    $this->userlog($notes,$status);
                   
                    return redirect()->route('saleseod.index')
                                    ->with('success','EOD Succesful');
                }else{
                    $notes = 'Sales. EOD. Create.';
                    $status = 'Failed';
                    $this->userlog($notes,$status);

                    return redirect()->route('saleseod.index')
                                    ->with('failed','EOD Failed');
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
                    $notes = 'Sales. EOD. Create.';
                    $status = 'Success';
                    $this->userlog($notes,$status);

                    return redirect()->route('saleseod.index')
                    ->with('success','EOD Succesful');
                }else{
                    $notes = 'Sales. EOD. Create.';
                    $status = 'Failed';
                    $this->userlog($notes,$status);

                    return redirect()->route('saleseod.index')
                                    ->with('failed','EOD Failed');
                }
                
            } 
        } catch(Exception $e) {
            dd($e);
        }
        
        if($saleseod){
            $notes = 'Sales. EOD. Create.';
            $status = 'Success';
            $this->userlog($notes,$status);

            return redirect()->route('saleseod.index')
                ->with('success','EOD Succesful');
        }else{
            $notes = 'Sales. EOD. Create.';
            $status = 'Failed';
            $this->userlog($notes,$status);

        return redirect()->route('saleseod.index')
                    ->with('failed','EOD Failed');
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
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->loaddata();
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

        if(Hash::check($request->password, auth()->user()->password)){
            if(auth()->user()->accesstype =='Cashier'){
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
                return redirect()->route('dashboardoverview.index');
            }          
            
        }else{
            $notes = 'Sales. EOD. Incorrect Password.';
            $status = 'Failed';
            $this->userlog($notes,$status);

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
                
                $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        
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
                        $builder->where('posted', "Y")
                            ->where('fully_paid', 'Y');
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
                            $notes = 'Sales. EOD. Create.';
                            $status = 'Success';
                            $this->userlog($notes,$status);
                         
                            return redirect()->route('saleseod.index')
                                            ->with('success','EOD Succesful');
                        }else{
                            $notes = 'Sales. EOD. Create.';
                            $status = 'Failed';
                            $this->userlog($notes,$status);

                            return redirect()->route('saleseod.index')
                                            ->with('failed','EOD Failed');
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
                            $notes = 'Sales. EOD. Create.';
                            $status = 'Success';
                            $this->userlog($notes,$status);

                            return redirect()->route('saleseod.index')
                            ->with('success','EOD Succesful');
                        }else{
                            $notes = 'Sales. EOD. Create.';
                            $status = 'Failed';
                            $this->userlog($notes,$status);

                            return redirect()->route('saleseod.index')
                                            ->with('failed','EOD Failed');
                        }
                        
                    } 
                } catch(Exception $e) {
                    dd($e);
                }
                
                if($saleseod){
                    $notes = 'Sales. EOD. Create.';
                    $status = 'Success';
                    $this->userlog($notes,$status);
                   
                    return redirect()->route('saleseod.index')
                    ->with('success','EOD Succesful');
                }else{
                    $notes = 'Sales. EOD. Create.';
                    $status = 'Failed';
                    $this->userlog($notes,$status);

                return redirect()->route('saleseod.index')
                            ->with('failed','EOD Failed');
                }
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Administrator'){
                return redirect()->route('dashboardoverview.index');
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Administrator'){
                return redirect()->route('dashboardoverview.index');
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');;
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Administrator'){
                return redirect()->route('dashboardoverview.index');
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');;
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Administrator'){
                return redirect()->route('dashboardoverview.index');
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');;
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Administrator'){
                return redirect()->route('dashboardoverview.index');
            }
        }else{
            return redirect()->route('dashboardoverview.index');;
        }
    }
}
