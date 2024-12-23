<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RenterCreateRequests;
use App\Http\Requests\RenterSearchRequests;
use App\Http\Requests\RenterUpdateRequests;
use App\Models\Renter;
use App\Models\branch;
use App\Models\cabinet;
use App\Models\branchlist;
use App\Models\user_login_log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ManageRenterController extends Controller
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
        $renter = Renter::where('accesstype',"Renters")
                            ->orderBy('status','asc')
                            ->paginate(5);
        $notes = 'Renter';
        $status = 'Success';
        $this->userlog($notes,$status);

        return view('manage.renters.index',compact('renter'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function editcabinet($cabinet){
        $cab = cabinet::where('cabid',$cabinet)->first();                      
                        
        $renter = Renter::where('rentersid', $cab->userid)->first();
                         
        $rent = Renter::where('accesstype','Renters')
        ->where(function(Builder $builder){
            $builder->where('status','Active')
                    ->orderBy('lastname','asc')
                    ;
        })->get();
       
        $branches = branch::all();  
        
        return view('manage.renters.cabinetedit',['branches' => $branches])
                            ->with(['rent' => $rent])
                            ->with(['renter' => $renter])
                            ->with(['cabinet' => $cab]);
    }

    public function updatecabinet(Request $request,$cabinets){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
       
        $cabinet = cabinet::findOrFail($cabinets);
        $rent = Renter::where('rentersid',$cabinet->userid)->first();
         
        

        $mod = 0;
        $mod = $cabinet->mod;
        

        if($cabinet->status == 'Active')
        {
            if($request->renter != 'Vacant'){
                $cabinets = cabinet::where('cabid', $rent->rentersid)
                ->update([
                'userid' => $rent->rentersid,
                'email' => $rent->email,
                'cabinetprice' => $request->cabinetprice,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                ]);

                $totalcabown = cabinet::where('userid', $cabinet->userid)->count();
        
                Renter::where('rentersid',$rent->rentersid)
                ->update([
                    'cabid' => $totalcabown + 1,
                ]);

                
                if ($cabinets) {
                    //query successful
                    $notes = 'Renter. Cabinet. Update, ' . $rent->lastname;
                    $status = 'Success';
                    $this->userlog($notes,$status);

                    return redirect()->route('managerenter.index')
                                ->with('success','Cabinet updated successfully.');
                }else{
                    $notes = 'Renter. Cabinet. Update';
                    $status = 'Failed';
                    $this->userlog($notes,$status);

                    return redirect()->route('managerenter.update')
                                ->with('failed','Cabinet update failed');
                }
            }else{

                $cabinets = cabinet::where('userid', $cabinet->userid)
                ->update([
                'userid' => 0,
                'email' => 'Vacant',
                'cabinetprice' => $request->cabinetprice,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                ]);

                $totalcabown = cabinet::where('userid', $cabinet->userid)->count();
                
                $rentercabUpdate = Renter::where('rentersid',$cabinet->userid)
                            ->update([
                                'cabid' => $totalcabown - 1,
                            ]);
                

                if ($cabinets) {
                    //query successful
                    $notes = 'Renter. Cabinet. Update, ' . $rent->lastname;
                    $status = 'Success';
                    $this->userlog($notes,$status);

                    return redirect()->route('managerenter.index')
                                ->with('success','Cabinet updated successfully.');
                }else{
                    $notes = 'Renter. Cabinet. Update, ' . $rent->lastname;
                    $status = 'Failed';
                    $this->userlog($notes,$status);

                    return redirect()->route('managerenter.update')
                                ->with('failed','Cabinet update failed');
                }
            }
            
        }else{
            $notes = 'Renter. Cabinet. Update, Inactive. ' . $rent->lastname;
            $status = 'Failed';
            $this->userlog($notes,$status);
            
            return redirect()->route('managecabinet.index')
                            ->with('failed','Cabinet Inactive');
        }
    }

    public function statuscabinet($cabid){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $cabinet = cabinet::findOrFail($cabid);
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

        $notes = 'Renter. Cabinet. Deactivate. ' . $cabinet->cabinetname;
        $status = 'Success';
        $this->userlog($notes,$status);

        return redirect()->route('managerenter.show',$cabinet->userid)
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

        $notes = 'Renter. Cabinet. Activate. ' . $cabinet->cabinetname;
        $status = 'Success';
        $this->userlog($notes,$status);

        
        return redirect()->route('managerenter.show',$cabinet->userid)
                            ->with('success','Cabinet Activated successfully');
        }
    }
    
    public function storedata(Request $request){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $br = branch::where('branchname',$request->branchname)->first();

        $renter = Renter::create([
            'avatar' => 'avatars/avatar-default.jpg',
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'birthdate' => $request->birthdate,
            'branchid' => $br->branchid,
            'branchname' => $br->branchname,
            'cabid' => 0,
            'cabinetname' => 'Null',
            'accesstype' => 'Renters',
            'created_by' => auth()->user()->email,
            'updated_by' => 'Null',
            'timerecorded' => $timenow,
            'mod' => 0,
            'status' => 'Active',
        ]);

        $renter_n = Renter::create([
            'avatar' => 'avatars/avatar-default.jpg',
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'birthdate' => $request->birthdate,
            'branchid' => $br->branchid,
            'branchname' => $br->branchname,
            'cabid' => 0,
            'cabinetname' => 'Null',
            'accesstype' => 'Renters',
            'created_by' => auth()->user()->email,
            'updated_by' => 'Null',
            'timerecorded' => $timenow,
            'mod' => 0,
            'status' => 'Active',
        ]);
        
        $fullname = $request->lastname . ', ' . $request->firstname . ' ' . $request->middlename;
        if ($renter) {
            //query successful
            $notes = 'Renter. Create. ' . $fullname;
            $status = 'Success';
            $this->userlog($notes,$status);

            return redirect()->route('managerenter.index')
                        ->with('success','Renter created successfully.');
        }else{
            $notes = 'Renter. Create. ' . $fullname;
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('managerenter.index')
                        ->with('success','Renter creation failed');
        }  
    }
    
    public function updatedata($request,$renter){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $mod = 0;
        $mod = $renter->mod;
        $br = branch::where('branchname',$request->branchname)->first();
        $fullname = $request->lastname . ', ' . $request->firstname . ' ' . $request->middlename;

        if($request->password == null){
            $renter =Renter::where('rentersid',$renter->userid)->update([
                'username' => $request->username,
                'email' => $request->email,
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'birthdate' => $request->birthdate,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                'status' => $request->status,
            ]);

            $renter_n =Renter::where('rentersid',$renter->userid)->update([
                'username' => $request->username,
                'email' => $request->email,
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'birthdate' => $request->birthdate,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                'status' => $request->status,
            ]);
        }else{
            $renter =Renter::where('rentersid',$renter->userid)->update([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'birthdate' => $request->birthdate,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                'status' => $request->status,
            ]);

            $renter_n = Renter::where('rentersid',$renter->userid)->update([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'birthdate' => $request->birthdate,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                'status' => $request->status,
            ]);
        }
        
        if($renter){
            $notes = 'Renter. Update. ' . $fullname;
            $status = 'Success';
            $this->userlog($notes,$status);
            
            return redirect()->route('managerenter.index')
                        ->with('success','Renter updated successfully');
        }else{
            $notes = 'Renter. Update. ' . $fullname;
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('managerenter.index')
                        ->with('failed','Renter update failed');
        }
    }
    
    public function destroydata($renter){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

        $rent = Renter::where('rentersid', $renter->rentersid)->first();
        $fullname = $rent->lastname . ', ' . $rent->firstname . ' ' . $rent->middlename;

        if($renter->status == 'Active')
        {
            Renter::where('rentersid', $rent->rentersid)
            ->update([
            'status' => 'Inactive'
            ]);
            Renter::where('rentersid', $rent->rentersid)
            ->update([
            'status' => 'Inactive'
            ]);

        $renter = Renter::wherenot('accesstype', 'Renters')->get();

        $notes = 'Renter. Deactivate. ' . $fullname;
        $status = 'Success';
        $this->userlog($notes,$status);
        
        return redirect()->route('managerenter.index')
            ->with('success','User Deactivated successfully'); 
        }
        elseif($renter->status == 'Inactive')
        {
            Renter::where('rentersid', $rent->rentersid)
            ->update([
            'status' => 'Active'
            ]);
            Renter::where('rentersid', $rent->rentersid)
            ->update([
            'status' => 'Active'
            ]);

        $renter = Renter::wherenot('accesstype', 'Renters')->get();

        $notes = 'Renter. Activate. ' . $fullname;
        $status = 'Success';
        $this->userlog($notes,$status);

        return redirect()->route('managerenter.index')
            ->with('success','User Activated successfully');
        }
    }

    public function renterinfo()
    {
        return view('manage.renters.create-renter-info');
    }

    public function renterregister(Request $request)
    {
        $n1 = strtoupper($request->firstname[0]);
        $n2 = strtoupper($request->middlename[0]);
        $n3 = strtoupper($request->lastname[0]);
        $n4 = preg_replace('/[-]+/', '', $request->birthdate);

        $newpassword = $n1 . $n2 . $n3 . $n4;

        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $fullname = $request->lastname . ', ' . $request->firstname . ' ' . $request->middlename;
        if(auth()->user()->accesstype == 'Administrator'){
            if($request->newrenter == 'Y'){
                if($request->password == $request->password_confirmation){
                    $renter = Renter::create([
                        'avatar' => 'avatars/avatar-default.jpg',
                        'username' => $request->username,
                        'email' => $request->email,
                        'password' => Hash::make($newpassword),
                        'firstname' => $request->firstname,
                        'middlename' => $request->middlename,
                        'lastname' => $request->lastname,
                        'birthdate' => $request->birthdate,
                        'mobile_primary' => $request->mobile_primary,
                        'mobile_secondary' => $request->mobile_secondary,
                        'homeno' => $request->homeno,
                        'branchid' => auth()->user()->branchid,
                        'branchname' => auth()->user()->branchname,
                        'cabid' => 0,
                        'cabinetname' => 'Null',
                        'accesstype' => 'Renters',
                        'created_by' => auth()->user()->email,
                        'updated_by' => 'Null',
                        'timerecorded' => $timenow,
                        'mod' => 0,
                        'status' => 'Active',
                    ]);

                    $renter_n = Renter::create([
                        'avatar' => 'avatars/avatar-default.jpg',
                        'username' => $request->username,
                        'email' => $request->email,
                        'password' => Hash::make($newpassword),
                        'firstname' => $request->firstname,
                        'middlename' => $request->middlename,
                        'lastname' => $request->lastname,
                        'birthdate' => $request->birthdate,
                        'mobile_primary' => $request->mobile_primary,
                        'mobile_secondary' => $request->mobile_secondary,
                        'homeno' => $request->homeno,
                        'branchid' => auth()->user()->branchid,
                        'branchname' => auth()->user()->branchname,
                        'cabid' => 0,
                        'cabinetname' => 'Null',
                        'accesstype' => 'Renters',
                        'created_by' => auth()->user()->email,
                        'updated_by' => 'Null',
                        'timerecorded' => $timenow,
                        'mod' => 0,
                        'status' => 'Active',
                    ]);

                    $rentersearch = Renter::where('firstname', $request->firstname)
                            ->where(function(Builder $builder) use($request){
                            $builder->where('lastname',$request->lastname)
                                    ->where('birthdate',$request->birthdate);
                                })->first();

                    $branchlistadd =branchlist::create([
                        'userid' => $rentersearch->userid,
                        'branchid' => auth()->user()->branchid,
                        'accesstype' => 'Renters',
                        'timerecorded'  => $timenow,
                        'cabcount' => 0,
                        'posted'  => 'N',
                        'created_by' => auth()->user()->email,
                        'updated_by' => 'Null',
                        'mod' => 0,
                        'status' => 'Active',
                    ]);


                    if($renter){
                        $notes = 'Renter. Register. ' . $fullname;
                        $status = 'Success';
                        $this->userlog($notes,$status);
                        
                        return redirect()->route('managerenter.index')
                                ->with('success','Renter Registered successfully.');
                    }else{
                        $notes = 'Renter. Register. ' . $fullname;
                        $status = 'Failed';
                        $this->userlog($notes,$status);

                        return redirect()->back()
                                    ->with('failed','Renter Registration failed. Save Error.');
                    }
        
                }else{
                    $notes = 'Renter. Register. Password Mismatched. ' . $fullname;
                    $status = 'Failed';
                    $this->userlog($notes,$status);
                   
                    return redirect()->back()
                                    ->with('failed','Renter Registration failed. Password Mismatched.');
                }
            
            }else{

                $branchlist = branchlist::where('userid', $request->userid)
                                ->where(function(Builder $builder) use($request){
                                $builder->where('branchid',auth()->user()->branchid);
                                })->first();
                                
                $branch = Renter::where('rentersid', $request->userid)->first();


                if(empty($branchlist))
                {

                    $branchlistadd =branchlist::create([
                        'userid' => $request->userid,
                        'branchid' => auth()->user()->branchid,
                        'accesstype' => 'Renters',
                        'timerecorded'  => $timenow,
                        'posted'  => 'N',
                        'created_by' => auth()->user()->email,
                        'updated_by' => 'Null',
                        'mod' => 0,
                        'status' => 'Active',
                    ]);

                    if($branchlistadd)
                    {
                        $notes = 'Renter. Register. ' . $fullname;
                        $status = 'Success';
                        $this->userlog($notes,$status);
                        
                        return redirect()->route('managerenter.index')
                                ->with('success','Renter Registered successfully.');
                    }else{
                        $notes = 'Renter. Register. ' . $fullname;
                        $status = 'Failed';
                        $this->userlog($notes,$status);

                        return redirect()->route('managerenter.index')
                                    ->with('failed','Renter Registration failed');
                    }  
                }
                else{
                    $notes = 'Renter. Register. Already Registered. ' . $fullname;
                    $status = 'Failed';
                    $this->userlog($notes,$status);

                    return redirect()->route('managerenter.index')
                                    ->with('failed','Renter Registration failed: Already Registered.');

                }
            }
        }else{
            $notes = 'Renter. Register. Already Registered. ' . $fullname;
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('dashboardoverview.index')->with('failed','Renter Registration failed.');
        }
        
    }

    public function renterlogin(Request $request)
    {
        
        if(auth()->user()->accesstype == 'Administrator'){
            $renter = Renter::where('firstname', $request->firstname)
                        ->where(function(Builder $builder) use($request){
                        $builder->where('lastname',$request->lastname)
                                ->where('birthdate',$request->birthdate);
                            })->first();
            if($renter){
                return view('manage.renters.create-renter-register',['renter' => $renter])->with('success','Renter Record Found.');
            }else{
                return view('manage.renters.create-renter-login')->with(['renterinfo' => $request]);
            }
            
        }else{
            return redirect()->route('dashboardoverview.index')->with('failed','Renter Registration failed.');
        }

    }

    public function search(Request $request)
    {
        $renter = Renter::where('accesstype',"Renters")
                    ->where(function(Builder $builder) use($request){
                        $builder->where('username','like',"%{$request->search}%")
                                ->orWhere('firstname','like',"%{$request->search}%")
                                ->orWhere('lastname','like',"%{$request->search}%")
                                ->orWhere('middlename','like',"%{$request->search}%")
                                ->orWhere('branchname','like',"%{$request->search}%")
                                ->orWhere('email','like',"%{$request->search}%")
                                ->orWhere('status','like',"%{$request->search}%");
                    })
                    ->orderBy('lastname',$request->orderrow)
                    ->paginate($request->pagerow);
    
        return view('manage.renters.index',compact('renter'))
            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
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
        return view('manage.renters.index');
    }

    public function selectbranch(){
        $branch = branch::orderBy('branchname', 'asc')->paginate(5);

        return view('manage.renters.create-selectbranch',['branch' => $branch])
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function createrenter($branches)
    {
        $branch = branch::where('branchid',$branches)
                        ->orderBy('branchname', 'asc')->first();
        $cabinet = cabinet::where('branchid',$branches)
                    ->where(function(Builder $builder){
                        $builder->where('email','Vacant')
                        ->orderBy('cabinetname', 'asc');
                    })->get();
       

        return view('manage.renters.create',['cabinet' => $cabinet])
                            ->with(['branch' => $branch]);
    }

    public function create()
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return view('manage.renters.create-renter-info'); 
            }elseif(auth()->user()->accesstype =='Administrator'){
                return view('manage.renters.create-renter-info');

            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }

        //$branch = branch::orderBy('branchname', 'asc')->get();

        //sreturn view('renters.create',['branch' => $branch]);
    }

    public function store(RenterCreateRequests $request)
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

    public function show($renter)
    {
        $renters = Renter::where('rentersid',$renter)->first();

        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                $cabinets = cabinet::where('userid',$renters->rentersid)
                                        ->orderBy('status','asc')
                                        ->orderBy('branchname','asc')
                                        ->paginate(5);
                            return view('manage.renters.show',['renter' => $renters])
                                ->with(compact('cabinets'))
                                ->with('i', (request()->input('page', 1) - 1) * 5);        
            }elseif(auth()->user()->accesstype =='Administrator'){
                $cabinets = cabinet::where('userid',$renters->rentersid)
                                        ->orderBy('status','asc')
                                        ->orderBy('branchname','asc')
                                        ->paginate(5);
                            return view('manage.renters.show',['renter' => $renters])
                                ->with(compact('cabinets'))
                                ->with('i', (request()->input('page', 1) - 1) * 5);
                                    }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($renter)
    {
        
        $renters = Renter::where('rentersid',$renter)->first();
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                $cabinet = cabinet::all();
                $branch = branch::all();
                return view('manage.renters.edit',['renter' => $renters])
                                ->with(['cabinet' => $cabinet])
                                ->with(['branch' => $branch]);         
            }elseif(auth()->user()->accesstype =='Administrator'){
                $cabinet = cabinet::all();
                $branch = branch::all();
                return view('manage.renters.edit',['renter' => $renters])
                                ->with(['cabinet' => $cabinet])
                                ->with(['branch' => $branch]); 
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RenterUpdateRequests $request, Renter $renter)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->updatedata($request,$renter);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->updatedata($request,$renter);
            }
            
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($renters)
    {
        $renter = Renter::where('rentersid',$renters)->first();
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->destroydata($renter);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->destroydata($renter);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }
}
