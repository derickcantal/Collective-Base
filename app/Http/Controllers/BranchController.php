<?php

namespace App\Http\Controllers;

use App\Models\branch;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;

class BranchController extends Controller
{
    public function search(Request $request)
    {
        $branches = branch::query()
                    ->where(function(Builder $builder) use($request){
                        $builder->where('branchname','like',"%{$request->search}%")
                                ->orWhere('branchaddress','like',"%{$request->search}%")
                                ->orWhere('branchcontact','like',"%{$request->search}%")
                                ->orWhere('branchemail','like',"%{$request->search}%")
                                ->orWhere('cabinetcount','like',"%{$request->search}%")
                                ->orWhere('status','like',"%{$request->search}%") 
                                ->orderBy('status','asc');
                    })
                    ->paginate(5);
    
        return view('branch.index',compact('branches'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->search;
        $branches = branch::query()
                    ->orderBy('status','asc')
                    ->paginate(5);
    
        return view('branch.index',compact('branches'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
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
    public function store(Request $request)
    {
        $branches = branch::create([
            'branchname' => $request->branchname,
            'branchaddress' => $request->branchaddress,
            'branchcontact' => $request->branchcontact,
            'branchemail' => $request->branchemail,
            'cabinetcount' => $request->cabinetcount,
            'created_by' => auth()->user()->email,
            'updated_by' => 'Null',
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(branch $branch)
    {
        //
    }
}
