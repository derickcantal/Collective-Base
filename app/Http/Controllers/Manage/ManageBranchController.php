<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;
use App\Http\Requests\CreateBranchRequest;
use App\Models\branch;
use App\Models\user_login_log;

class ManageBranchController extends Controller
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
        $branches = branch::query()
                    ->orderBy('status','asc')
                    ->orderBy('branchname','asc')
                    ->paginate(10);

        $notes = 'Branch';
        $status = 'Success';
        $this->userlog($notes,$status);
        
        return view('manage.branch.index',compact('branches'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }
    
    public function storedata($request){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $branches = branch::create([
            'branchname' => $request->branchname,
            'branchaddress' => $request->branchaddress,
            'branchcontact' => $request->branchcontact,
            'branchemail' => $request->branchemail,
            'cabinetcount' => $request->cabinetcount,
            'created_by' => auth()->user()->email,
            'updated_by' => 'Null',
            'timerecorded' => $timenow,
            'posted' => 'N',
            'mod' => 0,
            'status' => 'Active',
        ]);
    
        if ($branches) {
            //query successful
            $notes = 'Branch. Create. ' . $request->branchname;
            $status = 'Success';
            $this->userlog($notes,$status);
            
            return redirect()->route('managebranch.index')
                        ->with('success','Branch created successfully.');
        }else{
            $notes = 'Branch. Create ' . $request->branchname;
            $status = 'Failed';
            $this->userlog($notes,$status);
            
            return redirect()->route('managebranch.index')
                        ->with('failed','Branch creation failed');
        }  
    }
    
    public function updatedata($request, $branch){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $branch = branch::findOrFail($branch);
        $mod = 0;
        $mod = $branch->mod;

        $branchupdate = branch::where('branchid', $branch->branchid)->update([
                            'branchname' => $request->branchname,
                            'branchaddress' => $request->branchaddress,
                            'branchcontact' => $request->branchcontact,
                            'branchemail' => $request->branchemail,
                            'cabinetcount' => $request->cabinetcount,
                            'updated_by' => auth()->user()->email,
                            'posted' => 'N',
                            'mod' => $mod + 1,
                            'status' => 'Active',
                        ]);
        if($branchupdate){
            $notes = 'Branch. Update. ' . $request->branchname;
            $status = 'Success';
            $this->userlog($notes,$status);
            
            return redirect()->route('managebranch.index')
                            ->with('success','Branch updated successfully');
        }else{
            $notes = 'Branch. Update. ' . $request->branchname;
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('managebranch.index')
                            ->with('failed','Branch updated failed');
        }
            
    }
    
    public function destroydata($request,$branch){
        // dd($request,$branch);

        $mod = 0;
        $mod = $branch->mod;

        if($branch->status == 'Active')
        {
            branch::where('branchid', $branch->branchid)
            ->update([
            'status' => 'Inactive',
            'updated_by' => auth()->user()->email,
            'mod' => $mod + 1,
        ]);

        $notes = 'Branch. Deactivate. ' . $branch->branchname;
        $status = 'Success';
        $this->userlog($notes,$status);
 
        return redirect()->back()
                            ->with('success','Branch Deactivated successfully');
        }
        elseif($branch->status == 'Inactive')
        {
            branch::where('branchid', $branch->branchid)
            ->update([
            'status' => 'Active',
            'updated_by' => auth()->user()->email,
            'mod' => $mod + 1,
        ]);
        $notes = 'Branch. Activate. ' . $branch->branchname;
        $status = 'Success';
        $this->userlog($notes,$status);

        return redirect()->back()
                            ->with('success','Branch Activated successfully');
        }

    }

    public function search(Request $request)
    {
       
        $branches = branch::query()
                    ->where(function(Builder $builder) use($request){
                        $builder->where('branchname','like',"%{$request->search}%")
                                ->orWhere('branchaddress','like',"%{$request->search}%")
                                ->orWhere('branchcontact','like',"%{$request->search}%")
                                ->orWhere('branchemail','like',"%{$request->search}%")
                                ->orWhere('cabinetcount','like',"%{$request->search}%")
                                ->orWhere('created_by','like',"%{$request->search}%")
                                ->orWhere('updated_by','like',"%{$request->search}%")
                                ->orWhere('status','like',"%{$request->search}%");
                    })
                    ->orderBy('branchname',$request->orderrow)
                    ->paginate($request->pagerow);
    
        return view('manage.branch.index',compact('branches'))
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
        return view('manage.branch.index');
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
                return view('manage.branch.create');
            }elseif(auth()->user()->accesstype =='Administrator'){
                return view('manage.branch.create');
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBranchRequest $request)
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
    public function show(branch $branch)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return view('manage.branch.show',compact('branch'));
            }elseif(auth()->user()->accesstype =='Administrator'){
               return view('manage.branch.show',compact('branch'));
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($branch)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                $branch = branch::findOrFail($branch);
                return view('manage.branch.edit',['branch' => $branch]);
            }elseif(auth()->user()->accesstype =='Administrator'){
                $branch = branch::findOrFail($branch);
                return view('manage.branch.edit',['branch' => $branch]);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $branch)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->updatedata($request, $branch);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->updatedata($request, $branch);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
        
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,branch $branch)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->back();
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->back();
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->destroydata($request, $branch);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->destroydata($request, $branch);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }
}