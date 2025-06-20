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
use App\Models\users_temp;
use App\Models\user_login_log;
use App\Models\history_sales;
use App\Models\Sales;
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

    
    public function allrentersearch()
    {

    }
    public function allrenters()
    {
        $renter = Renter::query()
            ->paginate(10);

        $branch = branch::all();

        // dd($renter);
        return view('manage.renters.allrenters',compact('branch'))
        ->with(['renter' => $renter])
        ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function searchrenter(Request $request, $branchid)
    {
        $branch = branch::where('branchid',$branchid)->first();

         $renter = Renter::where('accesstype',"Renters")
                        ->where('branchid',$branchid)
                    ->where(function(Builder $builder) use($request){
                        $builder->where('username','like',"%{$request->search}%")
                                ->orWhere('firstname','like',"%{$request->search}%")
                                ->orWhere('lastname','like',"%{$request->search}%")
                                ->orWhere('branchname','like',"%{$request->search}%")
                                ->orWhere('email','like',"%{$request->search}%")
                                ->orWhere('status','like',"%{$request->search}%");
                    })
                    ->orderBy('lastname',$request->orderrow)
                    ->paginate($request->pagerow);
    
        return view('manage.renters.renterslist',compact('renter'))
            ->with(['branch' => $branch])
            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
    }

    public function renterslist($branchid){
        $branch = branch::where('branchid', $branchid)->first();

        $renter = branchlist::leftJoin('renters', function($join) {
                $join->on('branchlist.userid','=','renters.rentersid' );
                    })
                    ->where(function(Builder $builder) use($branch){
                        $builder
                        ->where('renters.accesstype', 'Renters')
                        ->where('branchlist.branchid', $branch->branchid);
                        })
              ->paginate(10);
        // $renter = Renter::where('branchid',$branch->branchid)
        //                     ->where('accesstype',"Renters")
        //                     ->orderBy('status','asc')
        //                     ->paginate(10);
        $notes = 'Renter. List';
        $status = 'Success';
        $this->userlog($notes,$status);

        return view('manage.renters.renterslist',compact('renter'))
        ->with(['branch' => $branch])
        ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function loaddata(){
        $branch = branch::orderBy('branchname', 'asc')->paginate(10);

        $renter = Renter::where('accesstype',"Renters")
                            ->orderBy('status','asc')
                            ->paginate(5);
        $notes = 'Renter';
        $status = 'Success';
        $this->userlog($notes,$status);

        return view('manage.renters.index',compact('branch'))
        ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function storedata(Request $request){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

        $n1 = strtoupper($request->firstname[0]);
        $n3 = strtoupper($request->lastname[0]);
        $n4 = preg_replace('/[-]+/', '', $request->birthdate);

        $newpassword = $n1 . $n3 . $n4;

        $fullname = $request->lastname . ', ' . $request->firstname;

        $br = branch::where('branchname',$request->branchname)->first();

        $usertemp = users_temp::where('firstname', $request->firstname)
            ->where(function(Builder $builder) use($request){
            $builder
                    //->where('middlename',$request->middlename)
                    ->where('lastname',$request->lastname)
                    ->where('birthdate',$request->birthdate);
                })->first();

        if(empty($usertemp))
        {
            $searchrenter = Renter::where('firstname', $request->firstname)
                ->where(function(Builder $builder) use($request){
                $builder->where('lastname',$request->lastname)
                        ->where('birthdate',$request->birthdate);
                    })->first();

            if(empty($searchrenter))
            {
                $searchemail = Renter::where('email',$request->email)->first();

                if(!empty($searchemail))
                {
                    // dd($searchemail,'found');
                    return redirect()->back()
                                ->with('failed','Renter Email Already Registered');

                }

                $renter = Renter::create([
                    'avatar' => 'avatars/avatar-default.jpg',
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($newpassword),
                    'firstname' => $request->firstname,
                    'middlename' => 'Null',
                    'lastname' => $request->lastname,
                    'birthdate' => $request->birthdate,
                    'branchid' => $br->branchid,
                    'branchname' => $br->branchname,
                    'mobile_primary' => $request->mobile_primary,
                    'cabid' => 0,
                    'cabinetname' => 'Null',
                    'accesstype' => 'Renters',
                    'created_by' => auth()->user()->email,
                    'updated_by' => 'Null',
                    'timerecorded' => $timenow,
                    'mod' => 0,
                    'status' => 'Active',
                ]);


                if($renter) {
                    //query successful
                    $notes = 'Renter. Create. ' . $fullname;
                    $status = 'Success';
                    $this->userlog($notes,$status);

                    return redirect()->back()
                                ->with('success','Renter created successfully.');
                }else{
                    $notes = 'Renter. Create. ' . $fullname;
                    $status = 'Failed';
                    $this->userlog($notes,$status);

                    return redirect()->back()
                                ->with('success','Renter creation failed');
                }  
            }else
            {
                $branchlistsearch = branchlist::where('userid', $searchrenter->rentersid)
                                ->where(function(Builder $builder) use($request,$br){
                                $builder->where('branchid',$br->branchid);
                                })->first();
                
                if(!empty($branchlistsearch))
                {
                    // dd($branchlistsearch,'not found');
                    return redirect()->back()
                                ->with('failed','Renter Already Registered to Branch.');
                }
                
                $branchlist =branchlist::create([
                            'userid' => $searchrenter->rentersid,
                            'branchid' => $br->branchid,
                            'accesstype' => 'Renters',
                            'timerecorded'  => $timenow,
                            'cabcount' => 0, 
                            'posted'  => 'N',
                            'created_by' => auth()->user()->email,
                            'updated_by' => 'Null',
                            'mod' => 0,
                            'status' => 'Active',
                ]);

                if ($branchlist) {
                    //query successful
                    $notes = 'Renter. Create. ' . $fullname;
                    $status = 'Success';
                    $this->userlog($notes,$status);

                    return redirect()->back()
                                ->with('success','Renter Added to Branch successfully.');
                }else{
                    $notes = 'Renter. Create. ' . $fullname;
                    $status = 'Failed';
                    $this->userlog($notes,$status);

                    return redirect()->back()
                                ->with('failed','Renter Adding to Branch failed');
                } 
            }
            return redirect()->back()
                        ->with('success','Renter Not Found');
        }else
        {
            dd($usertemp);
            return redirect()->back()
                        ->with('success','Renter Found');
        }

  

       
    }
    
    public function updatedata($request,$renters){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $mod = 0;
        $mod = $renters->mod;
        $br = branch::where('branchname',$request->branchname)->first();
       // $fullname = $request->lastname . ', ' . $request->firstname . ' ' . $request->middlename;
        $fullname = $request->lastname . ', ' . $request->firstname;

        if($request->password == null){
            $renter =Renter::where('rentersid',$renters->rentersid)->update([
                'username' => $request->username,
                'email' => $request->email,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'birthdate' => $request->birthdate,
                'mobile_primary' => $request->mobile_primary,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                'status' => $request->status,
            ]);

        
        }elseif($request->password == $request->password_confirmation){
            $renter =Renter::where('rentersid',$renters->rentersid)->update([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'birthdate' => $request->birthdate,
                'mobile_primary' => $request->mobile_primary,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                'status' => $request->status,
            ]);
        }else
        {
            return redirect()->back()
                        ->with('failed','Renter Password Mismatch.');
        }
        
        if($renter){
            $notes = 'Renter. Update. ' . $fullname;
            $status = 'Success';
            $this->userlog($notes,$status);
            
            return redirect()->route('managerenter.renterslist',$br->branchid)
                        ->with('success','Renter updated successfully');
        }else{
            $notes = 'Renter. Update. ' . $fullname;
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('managerenter.renterslist',$br->branchid)
                        ->with('failed','Renter update failed');
        }
    }
    
    public function destroydata($renter){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

        $rent = Renter::where('rentersid', $renter->rentersid)->first();
        //$fullname = $rent->lastname . ', ' . $rent->firstname . ' ' . $rent->middlename;
        $fullname = $rent->lastname . ', ' . $rent->firstname;

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
        
        return redirect()->back()
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

        return redirect()->back()
            ->with('success','User Activated successfully');
        }
    }

    public function search(Request $request)
    {
        $branch = branch::where('branchname','like',"%{$request->search}%")
                    ->orderBy('branchname',$request->orderrow)
                    ->paginate($request->pagerow);
    
        return view('manage.renters.index',compact('branch'))
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

    public function create($branchid)
    {
        $branch = branch::where('branchid',$branchid)->first();

        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return view('manage.renters.create',compact('branch')); 
            }elseif(auth()->user()->accesstype =='Administrator'){
                return view('manage.renters.create',compact('branch'));

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

    public function show($renter,$branchid)
    {
        $renter = Renter::where('rentersid',$renter)->first();

        $branch = branch::where('branchid',$branchid)->first();

        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor')
            {
                return view('manage.renters.show',compact('renter'))
                            ->with(['branch' => $branch]);
            }elseif(auth()->user()->accesstype =='Administrator'){
               return view('manage.renters.show',compact('renter'))
                            ->with(['branch' => $branch]);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($renter,$branchid)
    {
        
        $renters = Renter::where('rentersid',$renter)->first();

        $branch = branch::where('branchid',$branchid)->first();

        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return view('manage.renters.edit',['renter' => $renters])
                                ->with(['branch' => $branch]);         
            }elseif(auth()->user()->accesstype =='Administrator'){
                return view('manage.renters.edit',['renter' => $renters])
                                ->with(['branch' => $branch]); 
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RenterUpdateRequests $request, Renter $renters)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->updatedata($request,$renters);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->updatedata($request,$renters);
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
