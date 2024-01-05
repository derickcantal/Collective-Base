<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCabinetRequest;
use App\Models\cabinet;
use App\Models\branch;
use App\Models\Renters;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;

class CabinetController extends Controller
{
    public function loaddata(){
        $cabinets = cabinet::query()
                    ->orderBy('status','asc')
                    ->orderBy('branchname','asc')
                    ->orderBy('cabinetname','asc')
                    ->paginate(5);
    
        return view('cabinet.index',compact('cabinets'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function storedata($request){
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
        $cabinet = cabinet::findOrFail($cabinet);
        $mod = 0;
        $mod = $cabinet->mod;
        cabinet::where('cabid', $cabinet->cabid)->update([
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

            return redirect()->route('cabinet.index')
                            ->with('success','Branch updated successfully');
    }
    
    public function destroydata(){
    
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
    public function show(cabinet $cabinet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($cabinet)
    {
        $branches = branch::all();
        $cabinet = cabinet::findOrFail($cabinet);
        return view('cabinet.edit',['cabinet' => $cabinet])->with(['branches' => $branches]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $cabinet)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return view('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return view('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->updatedata($request, $cabinet);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->updatedata($request, $cabinet);
            }
        }else{
            return view('dashboard.index');
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cabinet $cabinet)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return view('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return view('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                
            }elseif(auth()->user()->accesstype =='Administrator'){
                
            }
        }else{
            return view('dashboard.index');
        }
    }
}
