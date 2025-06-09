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

        $renter = Renter::where('branchid',$branch->branchid)
                            ->where('accesstype',"Renters")
                            ->orderBy('status','asc')
                            ->paginate(10);
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
        $br = branch::where('branchname',$request->branchname)->first();

        $renter = Renter::create([
            'avatar' => 'avatars/avatar-default.jpg',
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'firstname' => $request->firstname,
            'middlename' => 'Null',
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

        //$fullname = $request->lastname . ', ' . $request->firstname . ' ' . $request->middlename;
        $fullname = $request->lastname . ', ' . $request->firstname;
        

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
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                'status' => $request->status,
            ]);

        
        }else{
            $renter =Renter::where('rentersid',$renters->rentersid)->update([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'firstname' => $request->firstname,
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

    public function search(Request $request)
    {
        $renter = Renter::where('accesstype',"Renters")
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

    public function show($renter)
    {
        $renter = Renter::where('rentersid',$renter)->first();

        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor')
            {
                return view('manage.renters.show',compact('renter'));
            }elseif(auth()->user()->accesstype =='Administrator'){
               return view('manage.renters.show',compact('renter'));
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
