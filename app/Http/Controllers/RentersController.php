<?php

namespace App\Http\Controllers;

use App\Http\Requests\RenterCreateRequests;
use App\Http\Requests\RenterSearchRequests;
use App\Http\Requests\RenterUpdateRequests;
use App\Models\Renters;
use App\Models\Renter;
use App\Models\branch;
use App\Models\cabinet;
use App\Models\branchlist;
use App\Models\user_login_log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RentersController extends Controller
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
        $renter = Renters::where('accesstype',"Renters")
                            ->orderBy('status','asc')
                            ->paginate(5);
        $notes = 'Renter';
        $status = 'Success';
        $this->userlog($notes,$status);

        return view('renters.index',compact('renter'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function editcabinet($cabinet){
        $cab = cabinet::where('cabid',$cabinet)->first();                      
                        
        $renter = Renters::where('userid', $cab->userid)->first();
                         
        $rent = Renters::where('accesstype','Renters')
        ->where(function(Builder $builder){
            $builder->where('status','Active')
                    ->orderBy('lastname','asc')
                    ;
        })->get();
       
        $branches = branch::all();  
        
        return view('renters.cabinetedit',['branches' => $branches])
                            ->with(['rent' => $rent])
                            ->with(['renter' => $renter])
                            ->with(['cabinet' => $cab]);
    }

    public function updatecabinet(Request $request,$cabinet){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $rent = Renters::where('userid',$cabinet)->first();
         
        $cabinet = cabinet::findOrFail($cabinet);

        $mod = 0;
        $mod = $cabinet->mod;
        

        if($cabinet->status == 'Active')
        {
            if($request->renter != 'Vacant'){
                $cabinets = cabinet::where('cabid', $rent->userid)
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
                    $notes = 'Renter. Cabinet. Update, ' . $rent->lastname;
                    $status = 'Success';
                    $this->userlog($notes,$status);

                    return redirect()->route('renters.index')
                                ->with('success','Cabinet updated successfully.');
                }else{
                    $notes = 'Renter. Cabinet. Update';
                    $status = 'Failed';
                    $this->userlog($notes,$status);

                    return redirect()->route('renters.update')
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
                
                $rentercabUpdate = Renters::where('userid',$cabinet->userid)
                            ->update([
                                'cabid' => $totalcabown - 1,
                            ]);
                

                if ($cabinets) {
                    //query successful
                    $notes = 'Renter. Cabinet. Update, ' . $rent->lastname;
                    $status = 'Success';
                    $this->userlog($notes,$status);

                    return redirect()->route('renters.index')
                                ->with('success','Cabinet updated successfully.');
                }else{
                    $notes = 'Renter. Cabinet. Update, ' . $rent->lastname;
                    $status = 'Failed';
                    $this->userlog($notes,$status);

                    return redirect()->route('renters.update')
                                ->with('failed','Cabinet update failed');
                }
            }
            
        }else{
            $notes = 'Renter. Cabinet. Update, Inactive. ' . $rent->lastname;
            $status = 'Failed';
            $this->userlog($notes,$status);
            
            return redirect()->route('cabinet.index')
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

        return redirect()->route('renters.show',$cabinet->userid)
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

        
        return redirect()->route('renters.show',$cabinet->userid)
                            ->with('success','Cabinet Activated successfully');
        }
    }
    
    public function storedata(Request $request){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $br = branch::where('branchname',$request->branchname)->first();

        $renter = Renters::create([
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

            return redirect()->route('renters.index')
                        ->with('success','Renter created successfully.');
        }else{
            $notes = 'Renter. Create. ' . $fullname;
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('renters.index')
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
            $renter =Renters::where('userid',$renter->userid)->update([
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

            $renter_n =Renter::where('userid',$renter->userid)->update([
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
            $renter =Renters::where('userid',$renter->userid)->update([
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

            $renter_n = Renter::where('userid',$renter->userid)->update([
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
            
            return redirect()->route('renters.index')
                        ->with('success','Renter updated successfully');
        }else{
            $notes = 'Renter. Update. ' . $fullname;
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('renters.index')
                        ->with('failed','Renter update failed');
        }
    }
    
    public function destroydata($renter){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

        $rent = Renters::where('userid', $renter->userid)->first();
        $fullname = $rent->lastname . ', ' . $rent->firstname . ' ' . $rent->middlename;

        if($renter->status == 'Active')
        {
            Renters::where('userid', $renter->userid)
            ->update([
            'status' => 'Inactive'
            ]);
            Renter::where('userid', $renter->userid)
            ->update([
            'status' => 'Inactive'
            ]);

        $renter = Renters::wherenot('accesstype', 'Renters')->get();

        $notes = 'Renter. Deactivate. ' . $fullname;
        $status = 'Success';
        $this->userlog($notes,$status);
        
        return redirect()->route('renters.index')
            ->with('success','User Deactivated successfully'); 
        }
        elseif($renter->status == 'Inactive')
        {
            Renters::where('userid', $renter->userid)
            ->update([
            'status' => 'Active'
            ]);
            Renter::where('userid', $renter->userid)
            ->update([
            'status' => 'Active'
            ]);

        $renter = Renters::wherenot('accesstype', 'Renters')->get();

        $notes = 'Renter. Activate. ' . $fullname;
        $status = 'Success';
        $this->userlog($notes,$status);

        return redirect()->route('renters.index')
            ->with('success','User Activated successfully');
        }
    }

    public function renterinfo()
    {
        return view('rentercashier.create-renter-info');
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
                    $renter = Renters::create([
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

                    $rentersearch = Renters::where('firstname', $request->firstname)
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
                        
                        return redirect()->route('renters.index')
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
                                
                $branch = Renters::where('userid', $request->userid)->first();


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
                        
                        return redirect()->route('renters.index')
                                ->with('success','Renter Registered successfully.');
                    }else{
                        $notes = 'Renter. Register. ' . $fullname;
                        $status = 'Failed';
                        $this->userlog($notes,$status);

                        return redirect()->route('renters.index')
                                    ->with('failed','Renter Registration failed');
                    }  
                }
                else{
                    $notes = 'Renter. Register. Already Registered. ' . $fullname;
                    $status = 'Failed';
                    $this->userlog($notes,$status);

                    return redirect()->route('renters.index')
                                    ->with('failed','Renter Registration failed: Already Registered.');

                }
            }
        }else{
            $notes = 'Renter. Register. Already Registered. ' . $fullname;
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('dashboard.index')->with('failed','Renter Registration failed.');
        }
        
    }

    public function renterlogin(Request $request)
    {
        
        if(auth()->user()->accesstype == 'Administrator'){
            $renter = Renters::where('firstname', $request->firstname)
                        ->where(function(Builder $builder) use($request){
                        $builder->where('lastname',$request->lastname)
                                ->where('birthdate',$request->birthdate);
                            })->first();
            if($renter){
                return view('renters.create-renter-register',['renter' => $renter])->with('success','Renter Record Found.');
            }else{
                return view('renters.create-renter-login')->with(['renterinfo' => $request]);
            }
            
        }else{
            return redirect()->route('dashboard.index')->with('failed','Renter Registration failed.');
        }

    }

    public function search(Request $request)
    {
        $renter = Renters::where('accesstype',"Renters")
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
    
        return view('renters.index',compact('renter'))
            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
    }
 
    public function index()
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->loaddata();
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->loaddata();
            }
        }else{
            return redirect()->route('dashboard.index');
        }
       
    }

    public function selectbranch(){
        $branch = branch::orderBy('branchname', 'asc')->paginate(5);

        return view('renters.create-selectbranch',['branch' => $branch])
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
       

        return view('renters.create',['cabinet' => $cabinet])
                            ->with(['branch' => $branch]);
    }

    public function create()
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return view('renters.create-renter-info'); 
            }elseif(auth()->user()->accesstype =='Administrator'){
                return view('renters.create-renter-info');

            }
        }else{
            return redirect()->route('dashboard.index');
        }

        //$branch = branch::orderBy('branchname', 'asc')->get();

        //sreturn view('renters.create',['branch' => $branch]);
    }

    public function store(RenterCreateRequests $request)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->storedata($request);         
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->storedata($request); 
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }

    public function show(Renters $renter)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                $cabinets = cabinet::where('userid',$renter->userid)
                                        ->orderBy('status','asc')
                                        ->orderBy('branchname','asc')
                                        ->paginate(5);
                            return view('renters.show',['renter' => $renter])
                                ->with(compact('cabinets'))
                                ->with('i', (request()->input('page', 1) - 1) * 5);        
            }elseif(auth()->user()->accesstype =='Administrator'){
                $cabinets = cabinet::where('userid',$renter->userid)
                                        ->orderBy('status','asc')
                                        ->orderBy('branchname','asc')
                                        ->paginate(5);
                            return view('renters.show',['renter' => $renter])
                                ->with(compact('cabinets'))
                                ->with('i', (request()->input('page', 1) - 1) * 5);
                                    }
        }else{
            return redirect()->route('dashboard.index');
        }
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Renters $renter)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                $cabinet = cabinet::all();
                $branch = branch::all();
                return view('renters.edit',['renter' => $renter])
                                ->with(['cabinet' => $cabinet])
                                ->with(['branch' => $branch]);         
            }elseif(auth()->user()->accesstype =='Administrator'){
                $cabinet = cabinet::all();
                $branch = branch::all();
                return view('renters.edit',['renter' => $renter])
                                ->with(['cabinet' => $cabinet])
                                ->with(['branch' => $branch]); 
            }
        }else{
            return redirect()->route('dashboard.index');
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RenterUpdateRequests $request, Renters $renter)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->updatedata($request,$renter);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->updatedata($request,$renter);
            }
            
        }else{
            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Renters $renter)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->destroydata($renter);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->destroydata($renter);
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }
}
