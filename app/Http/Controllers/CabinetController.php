<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCabinetRequest;
use App\Models\cabinet;
use App\Models\branch;
use App\Models\Renters;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;

class CabinetController extends Controller
{
    public function loaddata(){
        $cabinets = cabinet::query()
                    ->orderBy('status','asc')
                    ->orderBy('cabid','asc')
                    ->orderBy('branchname','asc')
                    ->paginate(5);
    
        return view('cabinet.index',compact('cabinets'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function storedata($request){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');
        $cabcount = cabinet::where('branchname',$request->branchname)->count();

        $br = branch::where('branchname',$request->branchname)->first();

        $cabn = cabinet::where('cabinetname',$request->cabinetname)
        ->where(function(Builder $builder) use($request){
            $builder->where('branchname',$request->branchname);
        })->first();

        
        if($br->cabinetcount >= $cabcount){
            if(empty($cabn->cabid)){      
                $cabinets = cabinet::create([
                    'cabinetname' => $request->cabinetname,
                    'branchid' => $br->branchid,
                    'branchname' => $br->branchname,
                    'userid' => 0,
                    'email' => 'Vacant',
                    'created_by' => auth()->user()->email,
                    'updated_by' => 'Null',
                    'timerecorded' => $timenow,
                    'posted' => 'N',
                    'mod' => 0,
                    'status' => 'Active',
                ]);
            
                if ($cabinets) {
                    //query successful
                    return redirect()->route('cabinet.index')
                                ->with('success','Cabinet created successfully.');
                }else{
                    return redirect()->route('cabinet.create')
                                ->with('failed','Cabinet creation failed');
                }
            }else{
                return redirect()->route('cabinet.create')
                                ->with('failed','Already Exists.');
                
            }  
            
        }else{
            return redirect()->route('cabinet.create')
                                    ->with('failed','Branch Maximum Cabinet Capacity Reached');
        }
    }
    
    public function updatedata($request,$cabinet){
        $rent = Renters::where('userid',$request->renter)->first();
        
        $cabinet = cabinet::findOrFail($cabinet);
        $mod = 0;
        $mod = $cabinet->mod;

        

        if($cabinet->status == 'Active')
        {
            if($request->renter != 'Vacant'){
                $cabrenter = cabinet::where('userid', $rent->userid)->get();
        
                $totalcabown = count($cabrenter);

                Renters::where('userid',$rent->userid)
                ->update([
                    'cabid' => $totalcabown,
                ]);
                $cabinets = cabinet::where('cabid', $cabinet->cabid)
                ->update([
                'userid' => $rent->userid,
                'email' => $rent->email,
                'cabinetprice' => $request->cabinetprice,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                ]);

                if ($cabinets) {
                    //query successful
                    return redirect()->route('cabinet.index')
                                ->with('success','Cabinet updated successfully.');
                }else{
                    return redirect()->route('cabinet.update')
                                ->with('failed','Cabinet update failed');
                }
            }else{
                $cabinets = cabinet::where('cabid', $cabinet->cabid)
                ->update([
                'userid' => 0,
                'email' => 'Vacant',
                'cabinetprice' => $request->cabinetprice,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                ]);

                if ($cabinets) {
                    //query successful
                    return redirect()->route('cabinet.index')
                                ->with('success','Cabinet updated successfully.');
                }else{
                    return redirect()->route('cabinet.update')
                                ->with('failed','Cabinet update failed');
                }
            }
            
        }else{
            return redirect()->route('cabinet.index')
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


        return redirect()->route('cabinet.index')
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


        return redirect()->route('cabinet.index')
                            ->with('success','Cabinet Activated successfully');
        }
    }

    public function search(Request $request)
    {
        $cabinets = cabinet::query()
                    ->where(function(Builder $builder) use($request){
                        $builder->where('cabinetname','like',"%{$request->search}%")
                                ->orWhere('branchname','like',"%{$request->search}%")
                                ->orWhere('userid','like',"%{$request->search}%")
                                ->orWhere('email','like',"%{$request->search}%")
                                ->orWhere('created_by','like',"%{$request->search}%")
                                ->orWhere('updated_by','like',"%{$request->search}%")
                                ->orWhere('status','like',"%{$request->search}%") 
                                ->orderBy('status','asc')
                                ->orderBy('branchname','asc')
                                ->orderBy('cabinetname','asc');
                    })
                    ->paginate(5);
    
        return view('cabinet.index',compact('cabinets'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
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
        $rent = Renters::where('accesstype','Renters')
        ->where(function(Builder $builder){
            $builder->where('status','Active')
                    ->orderBy('lastname','asc')
                    ;
        })->get();
       
        $branches = branch::all();
        return view('cabinet.create',['branches' => $branches])->with(['rent' => $rent]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCabinetRequest $request)
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
    public function show(cabinet $cabinet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($cabinet)
    {
        $cab = cabinet::where('cabinetname',$cabinet)->where('branchname',auth()->user()->branchname)->first();

        $rent = Renters::where('accesstype','Renters')
        ->where(function(Builder $builder){
            $builder->where('status','Active')
                    ->orderBy('lastname','asc')
                    ;
        })->get();
       
        $branches = branch::all();
        
        return view('cabinet.edit',['branches' => $branches])
                            ->with(['rent' => $rent])
                            ->with(['cabinet' => $cab]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $cabinet)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->updatedata($request, $cabinet);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->updatedata($request, $cabinet);
            }
        }else{
            return redirect()->route('dashboard.index');
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $cabinet)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->destroydata($request, $cabinet);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->destroydata($request, $cabinet);
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }
}
