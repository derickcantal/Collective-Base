<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Renter;
use App\Models\branch;
use App\Models\cabinet;
use App\Models\branchlist;
use App\Models\users_temp;
use App\Models\user_login_log;
use Illuminate\Support\Facades\Mail;
use App\Mail\RenterDue;

class ManageAllRenterController extends Controller
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

    public function sendmail($rentersid)
    {
        // return redirect()->back()
        //                 ->with('failed','Send Mail. Still on Progress.');

        $today = Carbon::now();
        $today->month;
        $today->year;

        $monthyear = $today->month . '-' . $today->year;

        $duedate = ($today->month + 1). '-5-' . $today->year;
        
        $renter =Renter::where('rentersid',$rentersid)->first();
 
        $fullname = $renter->firstname . ' ' . $renter->lastname;

        $cabinets = cabinet::where('userid',$renter->rentersid)->get();
        
        if(empty($cabinets))
        {
            return redirect()->back()
                        ->with('failed','Send Mail. No Cabinet Records Found.');
        }
        foreach ($cabinets as $cabinet){
            $mailcontent = [
                'fullname' => $fullname,
                'cabinetno' => $cabinet->cabinetname,
                'cabinetprice' => $cabinet->cabinetprice,
                'monthyear' => $monthyear,
                'duedate' => $duedate,
                'branch' => $cabinet->branchname,
            ];

            // dd($fullname, $renter, $cabinet, $monthyear,$duedate, $mailcontent);
            
            Mail::to($renter->email)->send(new RenterDue($mailcontent));
        }

        //  $request->message = 'Successfully Sent';
         return redirect()->back()->with('success','Email Sent.');
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
            
            return redirect()->route('manageallrenter.allrenters')
                        ->with('success','Renter updated successfully');
        }else{
            $notes = 'Renter. Update. ' . $fullname;
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('manageallrenter.allrenters')
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
    
        return view('manage.renters.allrenters',compact('renter'))
            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
    }

    /**
     * Display a listing of the resource.
     */
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($renter)
    {
        $renter = Renter::where('rentersid',$renter)->first();

        $branch = branch::where('branchid',$renter->branchid)->first();

        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor')
            {
                return view('manage.renters.allrentersshow',compact('renter'))
                            ->with(['branch' => $branch]);
            }elseif(auth()->user()->accesstype =='Administrator'){
               return view('manage.renters.allrentersshow',compact('renter'))
                            ->with(['branch' => $branch]);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
       
    }

    public function edit($renter)
    {
        
        $renters = Renter::where('rentersid',$renter)->first();

        $branch = branch::where('branchid',$renters->branchid)->first();

        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return view('manage.renters.allrentersedit',['renter' => $renters])
                                ->with(['branch' => $branch]);         
            }elseif(auth()->user()->accesstype =='Administrator'){
                return view('manage.renters.allrentersedit',['renter' => $renters])
                                ->with(['branch' => $branch]); 
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Renter $renters)
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
