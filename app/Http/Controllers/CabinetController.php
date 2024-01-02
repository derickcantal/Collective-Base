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
                                ->orderBy('status','asc');
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
        $request->search;
        $cabinets = cabinet::query()
                    ->orderBy('status','asc')
                    ->paginate(5);
    
        return view('cabinet.index',compact('cabinets'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
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
        $cabn = cabinet::where('cabinetname',$request->cabinetname)
        ->where(function(Builder $builder) use($request){
            $builder->where('branchname',$request->branchname);
        })->first();
        if(empty($cabn->cabid)){
            $cabinets = cabinet::create([
                'cabinetname' => $request->cabinetname,
                'branchid' => '1',
                'branchname' => $request->branchname,
                'userid' => auth()->user()->userid,
                'email' => auth()->user()->email,
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
                return redirect()->route('cabinet.index')
                            ->with('failed','Cabinet creation failed');
            }  
        }else{
            return redirect()->route('cabinet.index')
                            ->with('failed','Already Exists.');
            
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
    public function edit(cabinet $cabinet)
    {
        $cabinet = cabinet::findOrFail($cabinet);
        return view('cabinet.edit',['cabinet' => $cabinet]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $cabinet)
    {
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cabinet $cabinet)
    {
        //
    }
}
