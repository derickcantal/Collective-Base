<?php

namespace App\Http\Controllers;

use App\Http\Requests\RenterCreateRequests;
use App\Http\Requests\RenterSearchRequests;
use App\Http\Requests\RenterUpdateRequests;
use App\Models\Renters;
use App\Models\branch;
use App\Models\cabinet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Database\Eloquent\Builder;

class RentersController extends Controller
{
    public function loaddata(){
        $renter = Renters::where('accesstype',"Renters")
        ->orderBy('branchname','asc')
        ->orderBy('status','asc')
        ->paginate(5);

        return view('renters.index',compact('renter'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function storedata(Request $request){
        $br = branch::where('branchname',$request->branchname)->first();

        $cabn = cabinet::where('cabinetname',$request->cabinetname)
        ->where(function(Builder $builder) use($request){
            $builder->where('branchname',$request->branchname);
        })->first();

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
            'cabid' => $cabn->cabid,
            'cabinetname' => $cabn->cabinetname,
            'accesstype' => 'Renters',
            'created_by' => auth()->user()->email,
            'updated_by' => 'Null',
            'mod' => 0,
            'status' => 'Active',
        ]);
        
        $rent = Renters::where('email',$request->email)->first();
        
        $cabu = cabinet::where('cabid',$cabn->cabid)
                    ->update([
                        'userid' => $rent->userid,
                        'email' => $request->email,
                    ]);
        
        if ($renter) {
            //query successful
            return redirect()->route('renters.index')
                        ->with('success','User created successfully.');
        }else{
            return redirect()->route('renters.index')
                        ->with('success','User creation failed');
        }  
    }
    
    public function updatedata($request,$renter){
        $mod = 0;
        $mod = $renter->mod;
        if($request->password == null){
            $renter =Renters::where('userid',$renter->userid)->update([
                'username' => $request->username,
                'email' => $request->email,
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'birthdate' => $request->birthdate,
                'branchid' => '1',
                'branchname' => $request->branchname,
                'cabid' => '1',
                'cabinetname' => $request->cabinetname,
                'accesstype' => $request->accesstype,
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
                'branchid' => '1',
                'branchname' => $request->branchname,
                'cabid' => '1',
                'cabinetname' => $request->cabinetname,
                'accesstype' => $request->accesstype,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                'status' => $request->status,
            ]);
        }
        
        if($renter){
            return redirect()->route('renters.index')
                        ->with('success','Renter updated successfully');
        }else{
            return redirect()->route('renters.index')
                        ->with('failed','Renter update failed');
        }
    }
    
    public function destroydata($renter){
        if($renter->status == 'Active')
        {
            Renters::where('userid', $renter->userid)
            ->update([
            'status' => 'Inactive'
        ]);

        $renter = Renters::wherenot('accesstype', 'Renters')->get();
        
        return redirect()->route('renters.index')
            ->with('success','User Deactivated successfully'); 
        }
        elseif($renter->status == 'Inactive')
        {
            Renters::where('userid', $renter->userid)
            ->update([
            'status' => 'Active'
        ]);

        $renter = Renters::wherenot('accesstype', 'Renters')->get();
        
        return redirect()->route('renters.index')
            ->with('success','User Activated successfully');
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
                                ->orWhere('status','like',"%{$request->search}%") 
                                ->orderBy('status','asc');
                    })
                    ->paginate(5);
    
        return view('renters.index',compact('renter'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return view('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return view('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->loaddata();
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->loaddata();
            }
        }else{
            return view('dashboard.index');
        }
       
    }

    /**
     * Show the form for creating a new resource.
     */
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
        
        $cabinet = cabinet::where('branchname',$request->branchname)
                        ->orderBy('cabinetname', 'asc')->get();

        return view('renters.create',['cabinet' => $cabinet]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RenterCreateRequests $request)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return view('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return view('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->storedata($request);         
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->storedata($request); 
            }
        }else{
            return view('dashboard.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Renters $renter)
    {
        return view('renters.show',['renter' => $renter]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Renters $renter)
    {
        $cabinet = cabinet::all();
        $branch = branch::all();
        return view('renters.edit',['renter' => $renter])
                        ->with(['cabinet' => $cabinet])
                        ->with(['branch' => $branch]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RenterUpdateRequests $request, Renters $renter)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return view('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return view('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->updatedata($request,$renter);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->updatedata($request,$renter);
            }
            
        }else{
            return view('dashboard.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Renters $renter)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return view('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return view('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->destroydata($renter);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->destroydata($renter);
            }
        }else{
            return view('dashboard.index');
        }
    }
}
