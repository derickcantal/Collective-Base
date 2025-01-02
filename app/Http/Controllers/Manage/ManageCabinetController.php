<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateCabinetRequest;
use App\Models\cabinet;
use App\Models\branch;
use App\Models\Renters;
use App\Models\user_login_log;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;

class ManageCabinetController extends Controller
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

    public function loaddata(){
        $cabinets = cabinet::query()
                    ->orderBy('status','asc')
                    ->orderBy('cabid','asc')
                    ->orderBy('branchname','asc')
                    ->paginate(5);
        
        $notes = 'Cabinet';
        $status = 'Success';
        $this->userlog($notes,$status);

        return view('manage.cabinet.index',compact('cabinets'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function storedata($request){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $cabcount = (cabinet::where('branchname',$request->branchname)->count()) + 1;

        $br = branch::where('branchname',$request->branchname)->first();

        $cabn = cabinet::where('cabinetname',$request->cabinetname)
        ->where(function(Builder $builder) use($request){
            $builder->where('branchname',$request->branchname);
        })->first();
        //dd($br->cabinetcount >= $cabcount,$br->cabinetcount,$cabcount);
        if($br->cabinetcount >= $cabcount){
            if(empty($cabn->cabid)){      
                $cabinets = cabinet::create([
                    'cabinetname' => $request->cabinetname,
                    'cabinetprice' => $request->cabinetprice,
                    'branchid' => $br->branchid,
                    'branchname' => $br->branchname,
                    'rpmonth' => 0,
                    'rpyear'=> 0,
                    'userid' => 0,
                    'email' => 'Vacant',
                    'created_by' => auth()->user()->email,
                    'updated_by' => 'Null',
                    'fully_paid' => 'N',
                    'timerecorded' => $timenow,
                    'posted' => 'N',
                    'mod' => 0,
                    'status' => 'Active',
                ]);
            
                if ($cabinets) {
                    //query successful

                    $notes = 'Cabinet. Create ' . $request->cabinetname;
                    $status = 'Success';
                    $this->userlog($notes,$status);

                    return redirect()->route('managecabinet.index')
                                ->with('success','Cabinet created successfully.');
                }else{
                    $notes = 'Cabinet. Create ' . $request->cabinetname;
                    $status = 'Failed';
                    $this->userlog($notes,$status);

                    return redirect()->route('managecabinet.create')
                                ->with('failed','Cabinet creation failed');
                }
            }else{
                $notes = 'Cabinet. Create. Duplicate ' . $request->cabinetname;
                $status = 'Failed';
                $this->userlog($notes,$status);
 
                return redirect()->route('managecabinet.create')
                                ->with('failed','Already Exists.');
                
            }  
            
        }else{
            $notes = 'Cabinet. Create. Max Capacity' . $request->cabinetname;
            $status = 'Failed';
            $this->userlog($notes,$status);
            
            return redirect()->route('managecabinet.create')
                                    ->with('failed','Branch Maximum Cabinet Capacity Reached');
        }
    }
    
    public function updatedata($request,$cabinet){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $rent = Renters::where('userid',$cabinet)->first();
         
        $cabinet = cabinet::findOrFail($cabinet);

        $mod = 0;
        $mod = $cabinet->mod;

        

        if($cabinet->status == 'Active')
        {
            if($request->renter != 'Vacant'){
                $cabinets = cabinet::where('cabid', $cabinet->userid)
                ->update([
                'userid' => $rent->userid,
                'email' => $rent->email,
                'cabinetprice' => $request->cabinetprice,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                ]);

                $totalcabown = cabinet::where('userid', $cabinet->userid)->count();
        
                Renters::where('userid',$rent->userid)
                ->update([
                    'cabid' => $totalcabown + 1,
                ]);

                
                if ($cabinets) {
                    //query successful
                    $notes = 'Cabinet. Update ' . $cabinet->cabinetname;
                    $status = 'Success';
                    $this->userlog($notes,$status);

                    return redirect()->route('managecabinet.index')
                                ->with('success','Cabinet updated successfully.');
                }else{

                    $notes = 'Cabinet. Update ' . $cabinet->cabinetname;
                    $status = 'Failed';
                    $this->userlog($notes,$status);
 
                    return redirect()->route('managecabinet.index')
                                ->with('failed','Cabinet update failed. 1');
                }
            }else{
                $cabinets = cabinet::where('cabid', $cabinet->cabid)
                ->update([
                'userid' => 0,
                'email' => 'Vacant',
                'cabinetprice' => $request->cabinetprice,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                ]);


                if ($cabinets) {
                    //query successful
                    $notes = 'Cabinet. Update ' . $cabinet->cabinetname;
                    $status = 'Success';
                    $this->userlog($notes,$status);

                    return redirect()->route('managecabinet.index')
                                ->with('success','Cabinet updated successfully.');
                }else{
                    $notes = 'Cabinet. Update ' . $cabinet->cabinetname;
                    $status = 'Failed';
                    $this->userlog($notes,$status);

                    return redirect()->route('managecabinet.index')
                                ->with('failed','Cabinet update failed. 2');
                }
            }
            
        }else{
            $notes = 'Cabinet. Update. Inactive. ' . $cabinet->cabinetname;
            $status = 'Failed';
            $this->userlog($notes,$status);
            
            return redirect()->route('managecabinet.index')
                            ->with('failed','Cabinet Inactive');
        }
    }
    
    public function destroydata($request ,$cabinet){
        $cabinet = cabinet::findOrFail($cabinet);
        $mod = 0;
        $mod = $cabinet->mod;

        if($cabinet->status == 'Active')
        {
            cabinet::where('cabid', $cabinet->cabid)
            ->update([
            'status' => 'Inactive',
            'updated_by' => auth()->user()->email,
            'mod' => $mod + 1,
        ]);

        $notes = 'Cabinet. Deactivate. ' . $cabinet->cabinetname;
        $status = 'Success';
        $this->userlog($notes,$status);
 
        return redirect()->route('managecabinet.index')
                            ->with('success','Cabinet Deactivated successfully');
        }
        elseif($cabinet->status == 'Inactive')
        {
            cabinet::where('cabid', $cabinet->cabid)
            ->update([
            'status' => 'Active',
            'updated_by' => auth()->user()->email,
            'mod' => $mod + 1,
        ]);
        $notes = 'Cabinet. Activate. ' . $cabinet->cabinetname;
        $status = 'Success';
        $this->userlog($notes,$status);

        return redirect()->route('managecabinet.index')
                            ->with('success','Cabinet Activated successfully');
        }
    }

    public function search(Request $request)
    {
        $cabinets = cabinet::query()
                    ->where(function(Builder $builder) use($request){
                        $builder->where('cabinetname','like',"%{$request->search}%")
                                ->orWhere('branchname','like',"%{$request->search}%")
                                ->orWhere('userid','like',"%{$request->search}%")
                                ->orWhere('email','like',"%{$request->search}%")
                                ->orWhere('created_by','like',"%{$request->search}%")
                                ->orWhere('updated_by','like',"%{$request->search}%")
                                ->orWhere('status','like',"%{$request->search}%");
                    })
                    ->orderBy('cabid',$request->orderrow)
                    ->paginate($request->pagerow);
    
        return view('manage.cabinet.index',compact('cabinets'))
            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->loaddata();
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->loaddata();
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
        return view('manage.cabinet.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                $rent = Renters::where('accesstype','Renters')
                                ->where(function(Builder $builder){
                                    $builder->where('status','Active')
                                            ->orderBy('lastname','asc')
                                            ;
                                })->get();
                            
                $branches = branch::all();
                return view('manage.cabinet.create',['branches' => $branches])->with(['rent' => $rent]);
            }elseif(auth()->user()->accesstype =='Administrator'){
                $rent = Renters::where('accesstype','Renters')
                ->where(function(Builder $builder){
                    $builder->where('status','Active')
                            ->orderBy('lastname','asc')
                            ;
                })->get();
            
                $branches = branch::all();
                return view('manage.cabinet.create',['branches' => $branches])->with(['rent' => $rent]);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCabinetRequest $request)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->storedata($request);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->storedata($request);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(cabinet $cabinet)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->loaddata();
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->loaddata();
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($cabinet)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                $cab = cabinet::where('cabid',$cabinet)->first();                      
                        
                        
                $rent = Renters::where('accesstype','Renters')
                ->where(function(Builder $builder){
                    $builder->where('status','Active')
                            ->orderBy('lastname','asc')
                            ;
                })->get();
            
                $branches = branch::all();  
                
                return view('manage.cabinet.edit',['branches' => $branches])
                                    ->with(['rent' => $rent])
                                    ->with(['cabinet' => $cab]);
            }elseif(auth()->user()->accesstype =='Administrator'){
                $cab = cabinet::where('cabid',$cabinet)->first();                      
                        
                        
                $rent = Renters::where('accesstype','Renters')
                ->where(function(Builder $builder){
                    $builder->where('status','Active')
                            ->orderBy('lastname','asc')
                            ;
                })->get();
            
                $branches = branch::all();  
                
                return view('manage.cabinet.edit',['branches' => $branches])
                                    ->with(['rent' => $rent])
                                    ->with(['cabinet' => $cab]);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $cabinet)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->updatedata($request, $cabinet);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->updatedata($request, $cabinet);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $cabinet)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->destroydata($request, $cabinet);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->destroydata($request, $cabinet);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }
}
