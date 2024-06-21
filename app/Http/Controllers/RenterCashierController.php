<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Renters;
use App\Models\branch;
use App\Models\cabinet;
use App\Models\branchlist;
use App\Models\history_sales;
use App\Models\Sales;
use App\Models\users_temp;
use App\Models\user_login_log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;
use App\Http\Requests\RenterCreateRequests;
use App\Http\Requests\RenterSearchRequests;
use App\Http\Requests\RenterUpdateRequests;
use Illuminate\Support\Facades\DB;


class RenterCashierController extends Controller
{
    public function userlog($notes,$status){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s');
        
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
    public function cabinetupdate(Request $request)
    {
        $cabid = $request->cabid;
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s');
        $cabinet = cabinet::where('cabid',$cabid)->first();
        $mod = 0;
        $mod = $cabinet->mod;
        $cabinets = cabinet::where('cabid', $cabinet->cabid)
                ->update([
                'cabinetprice' => $request->cabinetprice,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                ]);


        if ($cabinets) {
            $notes = 'Renter. Cashier. Cabinet. Update';
            $status = 'Success';
            $this->userlog($notes,$status);
            
            return redirect()->route('renter.show',$cabinet->userid)
                        ->with('success','Cabinet Update Successful.');
        }else{
            $notes = 'Renter. Cashier. Cabinet. Update';
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('renter.show',$cabinet->userid)
                         ->with('failed','Cabinet Update Failed.');
        }

        
    }

    public function cabinetmodify(Request $request)
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s');

        $cabinet = cabinet::where('cabid',$request->cabid)
                        ->where(function(Builder $builder){
                            $builder->where('branchid',auth()->user()->branchid);
                        })->first();
        if(empty($cabinet))
        {
            $notes = 'Renter. Cashier. Cabinet. Modify. Account Not in Branch';
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('renter.index')
                    ->with('failed','Unknown Command.');
        }
        $branchlist = branchlist::where('userid', $cabinet->userid)
                        ->where(function(Builder $builder){
                            $builder->where('branchid',auth()->user()->branchid);
                        })->first();

        if(empty($branchlist))
        {
            $notes = 'Renter. Cashier. Cabinet. Modify. Account Not in Branch';
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('renter.index')
                    ->with('failed','Unknown Command.');
        }

        return view('rentercashier.show-cabinet-modify',['cabinet' => $cabinet]);
    }


    public function cabinetdelete(Request $request)
    {
        $notes = 'Renter. Cashier. Cabinet. Delete. Not Allowed';
        $status = 'Failed';
        $this->userlog($notes,$status);

        return redirect()->route('dashboard.index');
        $cabinet = cabinet::where('cabid',$request->cabid)->first();
        dd('delete');

    }

    public function cabinetcreate()
    {
        return redirect()->route('dashboard.index');
    }

    public function cabinetstore(Request $request)
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s');
        $cabuser = $request->cabuser;
        
        $renter = Renters::where('userid',$cabuser)->first();

        $cabinet = cabinet::where('cabid',$request->cabinetname)->first();

        $mod = 0;
        $mod = $cabinet->mod;
        
        if($cabinet->status == 'Active'){
            
        }else{
            $notes = 'Renter. Cashier. Cabinet. Assign. Inactive';
            $status = 'Failed';
            $this->userlog($notes,$status);
            
            return redirect()->route('renter.show',$renter->userid)
         ->with('failed','Inactive Cabinet.');
        }

        if($cabinet->email == 'Vacant'){
            
        }else{
            $notes = 'Renter. Cashier. Cabinet. Update. Occupied';
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('renter.show',$renter->userid)
         ->with('failed','Cabinet Occupied.');
        }

        $cabrenter = cabinet::where('userid', $renter->userid)->get();
        
        $totalcabown = count($cabrenter);

        Renters::where('userid',$renter->userid)
        ->update([
            'cabid' => $totalcabown + 1,
        ]);

        $cabinets = cabinet::where('cabid', $cabinet->cabid)
        ->update([
        'userid' => $renter->userid,
        'email' => $renter->email,
        'cabinetprice' => $request->cabinetprice,
        'updated_by' => auth()->user()->email,
        'mod' => $mod + 1,
        ]);

        $sales_history = history_sales::where('cabid', $cabinet->cabid)
        ->update([
        'userid' => $renter->userid,
        ]);

        $sales = Sales::where('cabid', $cabinet->cabid)
        ->update([
        'userid' => $renter->userid,
        ]);

        if ($cabinets) {
            //query successful

            $notes = 'Renter. Cashier. Cabinet. Assign.';
            $status = 'Success';
            $this->userlog($notes,$status);

            return redirect()->route('renter.show',$renter->userid)
                        ->with('success','Cabinet Assigned successfully.');
        }else{
            $notes = 'Renter. Cashier. Cabinet. Assign.';
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('renter.show',$renter->userid)
                        ->with('failed','Cabinet Assigned failed');
        }

        return redirect()->route('renter.show',$renter->userid)
         ->with('success','Cabinet Assigned Successfully.');
    }

    public function cabinetsearch(Request $request)
    {
        
    }
    public function cabinetadd(Request $request)
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s');
        $cabuser = $request->cabuser;

        $branchlist = branchlist::where('userid', $cabuser)
                        ->where(function(Builder $builder){
                            $builder->where('branchid',auth()->user()->branchid);
                        })->first();

        if(empty($branchlist))
        {
            $notes = 'Renter. Cashier. Cabinet. Assign. Account Not in Branch';
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('renter.index')
                    ->with('failed','Unknown Command.');
        }
        else
        {
            $renter = Renters::where('userid',$request->cabuser)->first();

            $cabinet = cabinet::where('branchname',auth()->user()->branchname)
                            ->where(function(Builder $builder){
                                $builder->where('email','=' ,'Vacant')
                                        ->where('status','=' ,'Active');
                            })->get();
            

            $rentername = $renter->lastname . ', ' . $renter->firstname;

            return view('rentercashier.show-cabinet-create',compact('renter'))
                                                ->with(compact('cabinet'))
                                                ->with(['cabuser' => $cabuser])
                                                ->with(['rentername' => $rentername]);
        }
        
    }
    public function search(Request $request)
    {
       
        if(auth()->user()->accesstype == 'Cashier'){

            $renter = DB::table('branchlist')
                    ->leftJoin('users', 'users.userid', '=', 'branchlist.userid')
                    ->where('users.accesstype', 'Renters')
                    ->where('branchlist.branchid', auth()->user()->branchid)
                    ->orderBy('users.lastname',$request->orderrow)
                    ->paginate($request->pagerow);

            
            return view('rentercashier.index',compact('renter'))
                            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
        }else{
            return redirect()->route('dashboard.index');
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->accesstype == 'Cashier'){

            $renter = DB::table('branchlist')
                    ->leftJoin('users', 'users.userid', '=', 'branchlist.userid')
                    ->where('users.accesstype', 'Renters')
                    ->where('branchlist.branchid', auth()->user()->branchid)
                    ->paginate(5);

            $notes = 'Renter. Cashier.';
            $status = 'Success';
            $this->userlog($notes,$status);

            return view('rentercashier.index',compact('renter'))
                            ->with('i', (request()->input('page', 1) - 1) * 5);
        }else{
            return redirect()->route('dashboard.index');
        }
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(auth()->user()->accesstype == 'Cashier'){
            return view('rentercashier.create-renter-info');
        }else{
            return redirect()->route('dashboard.index');
        }
    }
    public function renterinfo()
    {
        if(auth()->user()->accesstype == 'Cashier'){
            return view('rentercashier.create-renter-info');
        }else{
            return redirect()->route('dashboard.index');
        }
    }
    public function renterregister(Request $request)
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s');
        if(auth()->user()->accesstype == 'Cashier'){
            if($request->newrenter == 'Y'){
                if($request->password == $request->password_confirmation){
                    $renter = Renters::create([
                        'avatar' => 'avatars/avatar-default.jpg',
                        'username' => $request->username,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
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
                        users_temp::where('firstname',$request->firstname)
                        ->where(function(Builder $builder) use($request){
                            $builder->where('middlename',$request->middlename)
                                    ->where('lastname',$request->lastname)
                                    ->where('birthdate',$request->birthdate);
                            })
                        ->delete();
                        
                        $notes = 'Renter. Cashier. Register. ' . $request->lastname;
                        $status = 'Success';
                        $this->userlog($notes,$status);

                        return redirect()->route('renter.index')
                                ->with('success','Renter Registered successfully.');
                    }else{
                        $notes = 'Renter. Cashier. Register. ' . $request->lastname;
                        $status = 'Failed';
                        $this->userlog($notes,$status);

                        return redirect()->back()
                                    ->with('failed','Renter Registration failed. Save Error.');
                    }
        
                }else{
                    
                    $notes = 'Renter. Cashier. Register. Password Mismatched.' . $request->lastname;
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
                        'cabcount' => 0,
                        'posted'  => 'N',
                        'created_by' => auth()->user()->email,
                        'updated_by' => 'Null',
                        'mod' => 0,
                        'status' => 'Active',
                    ]);

                    if($branchlistadd)
                    
                    {
                        users_temp::where('firstname',$request->firstname)
                        ->where(function(Builder $builder) use($request){
                            $builder->where('middlename',$request->middlename)
                                    ->where('lastname',$request->lastname)
                                    ->where('birthdate',$request->birthdate);
                            })
                        ->delete();

                        $notes = 'Renter. Cashier. Register.' . $request->lastname;
                        $status = 'Success';
                        $this->userlog($notes,$status);

                        return redirect()->route('renter.index')
                                ->with('success','Renter Registered successfully.');
                    }else{
                        $notes = 'Renter. Cashier. Register.' . $request->lastname;
                        $status = 'Failed';
                        $this->userlog($notes,$status);

                        return redirect()->route('renter.index')
                                    ->with('failed','Renter Registration failed');
                    }  
                }
                else{
                    $notes = 'Renter. Cashier. Register. Duplicate' . $request->lastname;
                    $status = 'Success';
                    $this->userlog($notes,$status);

                    return redirect()->route('renter.index')
                                    ->with('failed','Renter Registration failed: Already Registered.');

                }
            }
        }else{
            $notes = 'Renter. Cashier. Register.' . $request->lastname;
            $status = 'Failed';
            $this->userlog($notes,$status);
            
            return redirect()->route('dashboard.index')
                        ->with('failed','Renter Registration failed.');
        }
        
    }
    public function renterlogin(Request $request)
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s');
        if(auth()->user()->accesstype == 'Cashier'){
            $renter = Renters::where('firstname', $request->firstname)
                        ->where(function(Builder $builder) use($request){
                        $builder->where('lastname',$request->lastname)
                                ->where('birthdate',$request->birthdate);
                            })->first();
            
            $usertemp = users_temp::where('firstname', $request->firstname)
            ->where(function(Builder $builder) use($request){
            $builder->where('lastname',$request->lastname)
                    ->where('birthdate',$request->birthdate);
                })->first();
            if($renter){
                return view('rentercashier.create-renter-register',['renter' => $renter])->with('success','Renter Record Found.');
            }elseif(empty($usertemp)){
                
                    $usertempadd =users_temp::create([
                        'firstname' => $request->firstname,
                        'middlename' => $request->middlename,
                        'lastname' => $request->lastname,
                        'birthdate' => $request->birthdate,
                        'branchid' => auth()->user()->branchid,
                        'branchname' => auth()->user()->branchname,
                        'accesstype' => 'Renters',
                        'timerecorded'  => $timenow,
                        'posted'  => 'N',
                        'created_by' => auth()->user()->email,
                        'updated_by' => 'Null',
                        'mod' => 0,
                        'status' => 'Active',
                    ]);

                
                    return view('rentercashier.create-renter-login')->with(['renterinfo' => $request]);
                
                
            }else{
                
                if($usertemp->branchid == auth()->user()->branchid){
                    return view('rentercashier.create-renter-login')->with(['renterinfo' => $request]);
                }else{
                    $notes = 'Renter. Cashier. Register. Application on progress' . $request->lastname;
                    $status = 'Failed';
                    $this->userlog($notes,$status);

                    return redirect()->back()
                                    ->with('failed','Renter Registration. Application on Progress.');
                }
                
            }
                

            
            
        }else{
            $notes = 'Renter. Cashier. Register.' . $request->lastname;
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('dashboard.index')->with('failed','Renter Registration failed.');
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return redirect()->route('dashboard.index');

        return redirect()->route('renter.index')
         ->with('success','Renter created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Renters $renter)
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s');
        $branchlists = branchlist::where('userid', $renter->userid)->first();

        if(empty($branchlists)){
            $notes = 'Renter. Cashier. Access to other Account.';
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('renter.index')
                            ->with('failed','Unknown Command');
        }
        if(auth()->user()->accesstype == 'Cashier'){
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
        }else{
            return redirect()->route('dashboard.index');
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Renters $renter)
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s');

        $branchlist = branchlist::where('userid', $renter->userid)
                        ->where(function(Builder $builder){
                            $builder->where('branchid',auth()->user()->branchid);
                        })->first();

        if(empty($branchlist))
        {
            $notes = 'Renter. Cashier. Modify. Access to other Account.';
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('renter.index')
                    ->with('failed','Unknown Command.');
        }

        if(auth()->user()->accesstype == 'Cashier'){
            return view('rentercashier.edit',['renter' => $renter]);
        }else{
            return redirect()->route('dashboard.index');
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s');
        if(auth()->user()->accesstype == 'Cashier'){
            $renter = Renters::where('userid',$id)->first();

            $mod = 0;
            $mod = $renter->mod;

            if($request->password == null){
                $renter =Renters::where('userid',$id)->update([
                    'username' => $request->username,
                    'email' => $request->email,
                    'firstname' => $request->firstname,
                    'middlename' => $request->middlename,
                    'lastname' => $request->lastname,
                    'birthdate' => $request->birthdate,
                    'updated_by' => auth()->user()->email,
                    'mod' => $mod + 1,
                ]);
            }else{
                $renter =Renters::where('userid',$id)->update([
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'firstname' => $request->firstname,
                    'middlename' => $request->middlename,
                    'lastname' => $request->lastname,
                    'birthdate' => $request->birthdate,
                    'updated_by' => auth()->user()->email,
                    'mod' => $mod + 1,
                ]);
            }

            if ($renter) {
                //query successful
                $notes = 'Renter. Cashier. Update. ' . $renter->lastname;
                $status = 'Success';
                $this->userlog($notes,$status);

                return redirect()->route('renter.index')
                            ->with('success','Renter updated successfully.');
            }else{
                $notes = 'Renter. Cashier. Update. ' . $renter->lastname;
                $status = 'Failed';
                $this->userlog($notes,$status);

                return redirect()->route('renter.index')
                            ->with('failed','Renter update failed');
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
        if(auth()->user()->accesstype == 'Cashier'){
            $renter = Renters::where('userid',$id)->first();

            $mod = 0;
            $mod = $renter->mod;

            if($renter->status == 'Active')
            {
                Renters::where('userid', $renter->userid)
                ->update([
                'status' => 'Inactive'
            ]);

            $notes = 'Renter. Cashier. Deactivate. ' . $renter->lastname;
            $status = 'Success';
            $this->userlog($notes,$status);

            return redirect()->route('renter.index')
                ->with('success','Renter Deactivated successfully'); 
            }
            elseif($renter->status == 'Inactive')
            {
                Renters::where('userid', $renter->userid)
                ->update([
                'status' => 'Active'
            ]);

            $notes = 'Renter. Cashier. Activate. ' . $renter->lastname;
            $status = 'Success';
            $this->userlog($notes,$status);

            return redirect()->route('renter.index')
                ->with('success','User Activated successfully');
            }
        }else{
            return redirect()->route('dashboard.index');
        }  
    }
}
