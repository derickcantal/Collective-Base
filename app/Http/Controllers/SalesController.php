<?php

namespace App\Http\Controllers;

use App\Models\sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Models\cabinet;

class SalesController extends Controller
{
    public function displayall()
    {
        $sales = sales::all();

        return view('dashboard.index',['sales' => $sales]);
    }

    public function search(Request $request)
    {
        $renter = sales::where('accesstype',"Renters")->where('status',"Active")
                    ->where(function(Builder $builder) use($request){
                        $builder
                                ->where('username','like',"%{$request->searchrbc}%")
                                ->orWhere('firstname','like',"%{$request->searchrbc}%")
                                ->orWhere('lastname','like',"%{$request->searchrbc}%")
                                ->orWhere('middlename','like',"%{$request->searchrbc}%")
                                ->orWhere('branchname','like',"%{$request->searchrbc}%")
                                ->orWhere('cabinetname','like',"%{$request->searchrbc}%")
                                ->orWhere('email','like',"%{$request->searchrbc}%")
                                ->orWhere('status','like',"%{$request->searchrbc}%") 
                                ->orderBy('status','asc');
                    })
                    ->paginate(5);

                    return view('sales.index',compact('sales'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    } 

    public function salescalc(Request $request)
    {
       
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = sales::get()->toQuery()
        ->orderBy('status','asc')
        ->paginate(5);

        return view('sales.index',compact('sales'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cabinet = cabinet::where('branchname',auth()->user()->branchname)->get();

        return view('sales.create',['cabinet' => $cabinet]);
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
    public function show($sales)
    {
        $sales = Sales::findOrFail($sales);
        return view('sales.show',['sales' => $sales]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($sales)
    {
        $sales = Sales::findOrFail($sales);
        return view('sales.edit',['sales' => $sales]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, sales $sales)
    {
        $sales = sales::findOrFail($sales);
        if($sales->status == 'Paid'){
            return redirect()->route('sales.index')
                            ->with('failed','Already Paid. Modifications Not Allowed');
        }elseif($sales->status == 'Unpaid'){
            $path = Storage::disk('public')->put('sales',$request->file('salesavatar'));
            // $path = $request->file('avatar')->store('avatars','public');
            
            $oldavatar = $sales->salesavatar;
            
            if($oldavatar == 'avatars/cash-default.jpg'){
                
            }else{
                Storage::disk('public')->delete($oldavatar);
            }
        

            sales::where('rpid', $sales)->update([
                'userid' => '1',
                'username' => $request->username,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'rpamount' => $request->rpamount,
                'rppaytype' => $request->rppaytype,
                'rpmonth' => $request->rpmonth,
                'rpyear' => $request->rpyear,
                'rpnotes' => $request->rpnotes,
                'branchid' => '1',
                'branchname' => $request->branchname,
                'cabid' => '1',
                'cabinetname' => $request->cabinetname,
                'salesavatar' => $path,
                'updated_by' => Auth()->user()->email,
                'status' => 'Paid',
            ]);

            return redirect()->route('sales.index')
                            ->with('success','Sales Payment updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(sales $sales)
    {
        //
    }
}
