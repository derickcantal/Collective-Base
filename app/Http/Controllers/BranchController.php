<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBranchRequest;
use App\Models\branch;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;

class BranchController extends Controller
{
    public function loaddata(){
        $branches = branch::query()
                    ->orderBy('status','asc')
                    ->orderBy('branchname','asc')
                    ->paginate(5);
    
        return view('branch.index',compact('branches'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function storedata($request){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');
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
            return redirect()->route('branch.index')
                        ->with('success','Branch created successfully.');
        }else{
            return redirect()->route('branch.index')
                        ->with('failed','Branch creation failed');
        }  
    }
    
    public function updatedata($request, $branch){
        $branch = branch::findOrFail($branch);
        $mod = 0;
        $mod = $branch->mod;
        branch::where('branchid', $branch->branchid)->update([
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

            return redirect()->route('branch.index')
                            ->with('success','Branch updated successfully');
    }
    
    public function destroydata(){
    
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
    
        return view('branch.index',compact('branches'))
            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('branch.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBranchRequest $request)
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

    /**
     * Display the specified resource.
     */
    public function show(branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($branch)
    {
        $branch = branch::findOrFail($branch);
        return view('branch.edit',['branch' => $branch]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $branch)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->updatedata($request, $branch);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->updatedata($request, $branch);
            }
        }else{
            return redirect()->route('dashboard.index');
        }
        
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(branch $branch)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                
            }elseif(auth()->user()->accesstype =='Renters'){
                
            }elseif(auth()->user()->accesstype =='Supervisor'){
                
            }elseif(auth()->user()->accesstype =='Administrator'){
                
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }
}
