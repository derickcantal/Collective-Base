<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateCabinetRequest;
use App\Models\cabinet;
use App\Models\branch;
use App\Models\Renter;
use App\Models\branchlist;
use App\Models\Sales;
use App\Models\history_sales;
use App\Models\archive_history_sales;
use App\Models\user_login_log;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;

class ManageCabinetController extends Controller
{
    public function archivestore(Request $request, $branchid, $cabid)
    {
        $branch = branch::where('branchid',$branchid)->first();
        $cabinet = cabinet::where('cabid',$cabid)->first();

        $startDate = Carbon::parse($request->startdate)->format('Y-m-d');
        $endDate = Carbon::parse($request->enddate)->format('Y-m-d');

        $branchcabinet = cabinet::where('branchid',$cabinet->branchid)->paginate(5);

        // dd($request,$branch,$cabinet);
        $archivedata = history_sales::where('cabid',$cabinet->cabid)
                    ->where(function(Builder $builder) use($startDate,$endDate) {
                        $builder->whereBetween('timerecorded', [$startDate .' 00:00:00', $endDate .' 23:59:59']);
                    })->get();
        // dd($archivedata->isEmpty());
        if($archivedata->isEmpty())
        {
             return redirect()->route('managecabinet.cabinetlist',$cabinet->branchid)
                            ->with(['cabinet' => $branchcabinet])
                            ->with('failed','No Data to Archive.')
                            ->with('i', (request()->input('page', 1) - 1) * 5);
        }

        $archive = history_sales::where('cabid',$cabinet->cabid)
                    ->where(function(Builder $builder) use($startDate,$endDate){
                        $builder->whereBetween('timerecorded', [$startDate .' 00:00:00', $endDate .' 23:59:59']);
                    })
                    ->each(function ($oldRecord) {
                        $newRecord = $oldRecord->replicate();
                        $newRecord->setTable('archive_history_sales');
                        $newRecord->save();
                        $oldRecord->delete();
                    });

        if($archive){
            return redirect()->route('managecabinet.cabinetlist',$cabinet->branchid)
                            ->with(['cabinet' => $branchcabinet])
                            ->with('success','Data Archived Successfully.')
                            ->with('i', (request()->input('page', 1) - 1) * 5);
        }else
        {
            return redirect()->route('managecabinet.cabinetlist',$cabinet->branchid)
                            ->with(['cabinet' => $branchcabinet])
                            ->with('failed','Data Archived Failed.')
                            ->with('i', (request()->input('page', 1) - 1) * 5);
        }

    }
    public function archive (Request $request, $branchid, $cabid)
    {
        $cabinet = cabinet::where('cabid',$cabid)->first();
        $branch = branch::where('branchid',$branchid)->first();

        // dd($cabinet,$branch);

         return view('manage.cabinet.archive',compact('branch'))
        ->with(['cabinet' => $cabinet]);
    }

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

    public function removerenter($cabid)
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $cabinet = cabinet::where('cabid',$cabid)->first();
        
        $mod = 0;
        $mod = $cabinet->mod;

        if($cabinet->email != 'Vacant')
        {

            $cabinets = cabinet::where('cabid', $cabinet->cabid)
                    ->update([
                    'userid' => 0,
                    'email' => 'Vacant',
                    'updated_by' => auth()->user()->email,
                    'mod' => $mod + 1,
                    ]);

            $totalcabown = cabinet::where('userid', $cabinet->userid)->count();
            
            $renterupdate = Renter::where('rentersid',$cabinet->userid)->update([
                        'cabid' => $totalcabown,
                    ]);

            $sales = Sales::where('cabid',$cabinet->cabid)->update([
                        'userid' => 0,
                        'username' => ' ',
                    ]);
            
            $history_sales = history_sales::where('cabid',$cabinet->cabid)->update([
                        'userid' => 0,
                        'username' => ' ',
                    ]);

            $branchcabinet = cabinet::where('branchid',$cabinet->branchid)->paginate(5);
            
            if ($cabinets) {
                //query successful
                $notes = 'Cabinet. Update ' . $cabinet->cabinetname;
                $status = 'Success';
                $this->userlog($notes,$status);

                return redirect()->route('managecabinet.cabinetlist',$cabinet->branchid)
                                ->with(['cabinet' => $branchcabinet])
                                ->with('success','Renter removed from Cabinet.')
                                ->with('i', (request()->input('page', 1) - 1) * 5);
            }else{
                $notes = 'Cabinet. Update ' . $cabinet->cabinetname;
                $status = 'Failed';
                $this->userlog($notes,$status);

                return redirect()->route('managecabinet.cabinetlist',$cabinet->branchid)
                                ->with(['cabinet' => $branchcabinet])
                                ->with('failed','Renter removal failed')
                                ->with('i', (request()->input('page', 1) - 1) * 5);
            }
        }else
        {
            $branchcabinet = cabinet::where('branchid',$cabinet->branchid)->paginate(5);

            return redirect()->route('managecabinet.cabinetlist',$cabinet->branchid)
                                ->with(['cabinet' => $branchcabinet])
                                ->with('failed','No Renter Assigned.')
                                ->with('i', (request()->input('page', 1) - 1) * 5);
        }
        
    }
    public function searchrenter(Request $request,$cabid)
    {
        $cabinet = cabinet::where('cabid',$cabid)->first();
        $renter = Renter::where('accesstype',"Renters")
                        ->where(function(Builder $builder) use($request){
                            $builder->where('firstname','like',"%{$request->search}%")
                                    ->orWhere('lastname','like',"%{$request->search}%")
                                    ->orWhere('email','like',"%{$request->search}%");
                        })->orderBy('lastname',$request->orderrow)
                          ->paginate($request->pagerow);
        $notes = 'Renter';
        $status = 'Success';
        $this->userlog($notes,$status);

        return view('manage.cabinet.allrenters',compact('renter'))
        ->with(['cabinet' => $cabinet])
        ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);

    }
    public function selectrenter($cabid,$rentersid)
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        
        $cabinet = cabinet::where('cabid',$cabid)->first();
        $renter = Renter::where('rentersid',$rentersid)->first();

        $mod = $cabinet->mod;

        $branchlist = branchlist::where('userid',$renter->rentersid)
                                ->where('branchid',$cabinet->branchid)
                                ->first();

        // dd(empty($branchlist));

        if(empty($branchlist))
        {
            $branchlistadd = branchlist::create([
                            'userid' => $renter->rentersid,
                            'branchid' => $cabinet->branchid,
                            'accesstype' => 'Renters',
                            'timerecorded'  => $timenow,
                            'cabcount' => 0, 
                            'posted'  => 'N',
                            'created_by' => auth()->user()->email,
                            'updated_by' => 'Null',
                            'mod' => 0,
                            'status' => 'Active',
                        ]);
        }

        if($cabinet->email == 'Vacant')
        {
            $cabinets = cabinet::where('cabid', $cabinet->cabid)->update([
                'userid' => $renter->rentersid,
                'email' => $renter->email,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                ]);

            $totalcabown = cabinet::where('userid', $renter->rentersid)->count();
            
            $renterupdate = Renter::where('rentersid',$renter->rentersid)->update([
                        'cabid' => $totalcabown,
                    ]);
            
            $sales = Sales::where('cabid',$cabinet->cabid)->update([
                        'userid' => $renter->rentersid,
                        'username' => $renter->email,
                    ]);
            
            $history_sales = history_sales::where('cabid',$cabinet->cabid)->update([
                        'userid' => $renter->rentersid,
                        'username' => $renter->email,
                    ]);

            $branchcabinet = cabinet::where('branchid',$cabinet->branchid)->paginate(5);

            return redirect()->route('managecabinet.cabinetlist',$cabinet->branchid)
                            ->with(['cabinet' => $branchcabinet])
                            ->with('success','Renter Assigned Successfully.')
                            ->with('i', (request()->input('page', 1) - 1) * 5);
        }
        else
        {
            $branchcabinet = cabinet::where('branchid',$cabinet->branchid)->paginate(5);

            return redirect()->route('managecabinet.cabinetlist',$cabinet->branchid)
                            ->with(['cabinet' => $branchcabinet])
                            ->with('failed','Cabinet has already assigned Renter.')
                            ->with('i', (request()->input('page', 1) - 1) * 5);
        }

    }
    public function allrenters($cabid)
    {
        $cabinet = cabinet::where('cabid',$cabid)->first();
        $renter = Renter::where('accesstype',"Renters")
                            ->orderBy('status','asc')
                            ->paginate(10);
        $notes = 'Renter';
        $status = 'Success';
        $this->userlog($notes,$status);

        return view('manage.cabinet.allrenters',compact('renter'))
        ->with(['cabinet' => $cabinet])
        ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function searchcabinet(Request $request,$branchid)
    {
        $branch = branch::where('branchid',$branchid)->first();

        $cabinets = cabinet::where('branchid',$branch->branchid)
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
    
        return view('manage.cabinet.cabinetlist',compact('cabinets'))
            ->with(['branch' => $branch])
            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
    }
    public function cabinetlist($branchid){

        $branch = branch::where('branchid',$branchid)->first();

        $cabinets = cabinet::where('branchid',$branch->branchid)
                    ->orderBy('status','asc')
                    ->orderBy('cabid','asc')
                    ->orderBy('branchname','asc')
                    ->paginate(5);
        
        $notes = 'Cabinet. List';
        $status = 'Success';
        $this->userlog($notes,$status);

        return view('manage.cabinet.cabinetlist',compact('cabinets'))
            ->with(['branch' => $branch])
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function loaddata(){
        $branches = branch::query()
                    ->orderBy('status','asc')
                    ->orderBy('branchname','asc')
                    ->paginate(10);

        $notes = 'Branch. Select.';
        $status = 'Success';
        $this->userlog($notes,$status);

        return view('manage.cabinet.index',compact('branches'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }
    
    public function storedata($request,$branchid){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $cabcount = (cabinet::where('branchname',$request->branchname)->count()) + 1;

        $br = branch::where('branchid',$branchid)->first();

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

                    return redirect()->back()
                                ->with('success','Cabinet created successfully.');
                }else{
                    $notes = 'Cabinet. Create ' . $request->cabinetname;
                    $status = 'Failed';
                    $this->userlog($notes,$status);

                    return redirect()->back()
                                ->with('failed','Cabinet creation failed');
                }
            }else{
                $notes = 'Cabinet. Create. Duplicate ' . $request->cabinetname;
                $status = 'Failed';
                $this->userlog($notes,$status);
 
                return redirect()->back()
                                ->with('failed','Already Exists.');
                
            }  
            
        }else{
            $notes = 'Cabinet. Create. Max Capacity' . $request->cabinetname;
            $status = 'Failed';
            $this->userlog($notes,$status);
            
            return redirect()->back()
                    ->with('failed','Branch Maximum Cabinet Capacity Reached');
        }
    }
    
    public function updatedata($request,$cabinet){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $cabinet = cabinet::where('cabid',$cabinet)->first();
        
        $mod = 0;
        $mod = $cabinet->mod;

        if($cabinet->status == 'Active')
        {
            $rent = Renter::where('rentersid',$request->renter)->first();

            // dd($cabinet,$request);
            $cabinets = cabinet::where('cabid', $cabinet->cabid)
            ->update([
            'cabinetprice' => $request->cabinetprice,
            'updated_by' => auth()->user()->email,
            'mod' => $mod + 1,
            ]);

            $branchcabinet = cabinet::where('branchid',$cabinet->branchid)->paginate(5);
            
            if ($cabinets) {
                //query successful
                $notes = 'Cabinet. Update ' . $cabinet->cabinetname;
                $status = 'Success';
                $this->userlog($notes,$status);

                return redirect()->route('managecabinet.cabinetlist',$cabinet->branchid)
                                ->with(['cabinet' => $branchcabinet])
                                ->with('success','Cabinet updated successfully.')
                                ->with('i', (request()->input('page', 1) - 1) * 5);
            }else{

                $notes = 'Cabinet. Update ' . $cabinet->cabinetname;
                $status = 'Failed';
                $this->userlog($notes,$status);

                return redirect()->route('managecabinet.cabinetlist',$cabinet->branchid)
                                ->with(['cabinet' => $branchcabinet])
                                ->with('failed','Cabinet update failed. 1')
                                ->with('i', (request()->input('page', 1) - 1) * 5);
                            
            }
            
        }else{
            $notes = 'Cabinet. Update. Inactive. ' . $cabinet->cabinetname;
            $status = 'Failed';
            $this->userlog($notes,$status);
            
            return redirect()->back()
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
 
        return redirect()->back()
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

        return redirect()->back()
                            ->with('success','Cabinet Activated successfully');
        }
    }

    public function search(Request $request)
    {
        $branches = branch::query()
                    ->where(function(Builder $builder) use($request){
                        $builder->where('branchname','like',"%{$request->search}%")
                                ->orWhere('status','like',"%{$request->search}%");
                    })
                    ->orderBy('branchid',$request->orderrow)
                    ->paginate($request->pagerow);
    
        return view('manage.cabinet.index',compact('branches'))
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
    public function create($branchid)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                $rent = Renter::where('accesstype','Renters')
                                ->where(function(Builder $builder){
                                    $builder->where('status','Active')
                                            ->orderBy('lastname','asc')
                                            ;
                                })->get();
                            
                $branch = branch::where('branchid',$branchid)->first();
                return view('manage.cabinet.create',['branch' => $branch])->with(['rent' => $rent]);
            }elseif(auth()->user()->accesstype =='Administrator'){
                $rent = Renter::where('accesstype','Renters')
                ->where(function(Builder $builder){
                    $builder->where('status','Active')
                            ->orderBy('lastname','asc')
                            ;
                })->get();
            
                $branch = branch::where('branchid',$branchid)->first();
                return view('manage.cabinet.create',['branch' => $branch])->with(['rent' => $rent]);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCabinetRequest $request,$branchid)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->storedata($request,$branchid);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->storedata($request,$branchid);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($cabinet)
    {
        $cabinets = cabinet::where('cabid',$cabinet)->first();

        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return view('manage.cabinet.show',compact('cabinets'));
            }elseif(auth()->user()->accesstype =='Administrator'){
                return view('manage.cabinet.show',compact('cabinets'));
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
                        
                        
                $rent = Renter::where('accesstype','Renters')
                ->where(function(Builder $builder){
                    $builder->where('status','Active');
                })->orderBy('lastname','asc')
                    ->get();
            
                $branches = branch::where('branchid',$cab->branchid)->first();  
                
                return view('manage.cabinet.edit',['branches' => $branches])
                                    ->with(['rent' => $rent])
                                    ->with(['cabinet' => $cab]);
            }elseif(auth()->user()->accesstype =='Administrator'){
                $cab = cabinet::where('cabid',$cabinet)->first();                      
                        
                        
                $rent = Renter::where('accesstype','Renters')
                ->where(function(Builder $builder){
                    $builder->where('status','Active');
                })->orderBy('lastname','asc')
                    ->get();
            
                $branches = branch::where('branchid',$cab->branchid)->first(); 

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
