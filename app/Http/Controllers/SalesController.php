<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Models\cabinet;

class SalesController extends Controller
{

    public function loaddata(){
        $sales = Sales::orderBy('status','asc')
        ->paginate(5);

        return view('sales.index',compact('sales'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function loaddata_cashier(){
        $sales = Sales::where('branchname',auth()->user()->branchname)
        ->orderBy('status','asc')
        ->paginate(5);

        return view('sales.index',compact('sales'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function storedata($request){
        if(empty($request->snotes)){
            $path = Storage::disk('public')->put('sales',$request->file('salesavatar'));
            
            $sales = Sales::create([
                'salesavatar' => $path,
                'salesname' => 'Null',
                'cabid' => 1,
                'cabinetname' => $request->cabinetname,
                'productname' => $request->productname,
                'qty' => $request->qty,
                'origprice' => 0,
                'srp' => $request->srp,
                'total' => $request->qty * $request->srp,
                'grandtotal' => 0,
                'userid' => auth()->user()->userid,
                'username' => auth()->user()->username,
                'accesstype' => auth()->user()->accesstype,
                'branchid' => '1',
                'branchname' => auth()->user()->branchname,
                'collected_status' => 'Pending',
                'returned' => 'N',
                'snotes' => 'Null',
                'posted' => 'N',
                'mod' => 0,
                'created_by' => auth()->user()->email,
                'updated_by' => 'Null',
                'status' => 'Unposted',
            ]);
        }else{
            $path = Storage::disk('public')->put('sales',$request->file('salesavatar'));
            
            $sales = Sales::create([
                'salesavatar' => $path,
                'salesname' => 'Null',
                'cabid' => 1,
                'cabinetname' => $request->cabinetname,
                'productname' => $request->productname,
                'qty' => $request->qty,
                'origprice' => 0,
                'srp' => $request->srp,
                'total' => $request->qty * $request->srp,
                'grandtotal' => 0,
                'userid' => auth()->user()->userid,
                'username' => auth()->user()->username,
                'accesstype' => auth()->user()->accesstype,
                'branchid' => '1',
                'branchname' => auth()->user()->branchname,
                'collected_status' => 'Pending',
                'returned' => 'N',
                'snotes' => $request->snotes,
                'posted' => 'N',
                'mod' => 0,
                'created_by' => auth()->user()->email,
                'updated_by' => 'Null',
                'status' => 'Unposted',
            ]);
        }
        
    
        if ($sales) {
            //query successful
            return redirect()->route('sales.index')
                        ->with('success','Sales created successfully.');
        }else{
            return redirect()->route('sales.index')
                        ->with('failed','Sales creation failed');
        }  
    }
    
    public function updatedata($request,$sales){
        $sales = Sales::findOrFail($sales);
        $mod = 0;
        $mod = $sales->mod;
        if($sales->mod == '1'){
            return redirect()->route('sales.index')
                            ->with('failed','Transaction completed. Modifications Not Allowed');
        }elseif($sales->mod == '0'){
            $path = Storage::disk('public')->put('sales',$request->file('salesavatar'));
            // $path = $request->file('avatar')->store('avatars','public');
            
            $oldavatar = $sales->salesavatar;
            
            if($oldavatar == 'avatars/cash-default.jpg'){
                
            }else{
                Storage::disk('public')->delete($oldavatar);
            }

            Sales::where('salesid', $sales->salesid)->update([
                'salesavatar' => $path,
                'salesname' => 'Null',
                'cabid' => 1,
                'cabinetname' => $request->cabinetname,
                'productname' => $request->productname,
                'qty' => $request->qty,
                'origprice' => 0,
                'srp' => $request->srp,
                'total' => $request->qty * $request->srp,
                'grandtotal' => 0,
                'userid' => auth()->user()->userid,
                'username' => auth()->user()->username,
                'accesstype' => auth()->user()->accesstype,
                'branchid' => '1',
                'branchname' => auth()->user()->branchname,
                'collected_status' => 'Pending',
                'returned' => 'N',
                'snotes' => $request->snotes,
                'posted' => 'N',
                'mod' => $mod + 1,
                'updated_by' => auth()->user()->email,
                'status' => 'Unposted',
            ]);

            return redirect()->route('sales.index')
                            ->with('success','Sales Payment updated successfully');
        }
    }
    
    public function destroydata(){
    
    }


    public function displayall()
    {
        $sales = Sales::all();

        return view('dashboard.index',['sales' => $sales]);
    }

    public function search(Request $request)
    {
        $sales = Sales::query()
                    ->where(function(Builder $builder) use($request){
                        $builder
                                ->where('cabinetname','like',"%{$request->search}%")
                                ->orWhere('productname','like',"%{$request->search}%")
                                ->orWhere('qty','like',"%{$request->search}%")
                                ->orWhere('srp','like',"%{$request->search}%")
                                ->orWhere('total','like',"%{$request->search}%")
                                ->orWhere('username','like',"%{$request->search}%")
                                ->orWhere('branchname','like',"%{$request->search}%")
                                ->orWhere('snotes','like',"%{$request->search}%") 
                                ->orderBy('status','asc');
                    })
                    ->paginate(5);

                    return view('sales.index',compact('sales'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    } 

     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return $this->loaddata_cashier();
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
        $cabinet = cabinet::where('branchname',auth()->user()->branchname)->get();

        return view('sales.create',['cabinet' => $cabinet]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return $this->storedata($request); 
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
    public function update(Request $request, $sales)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return $this->updatedata($request, $sales);
            }elseif(auth()->user()->accesstype =='Renters'){
                return view('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->updatedata($request, $sales);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->updatedata($request, $sales);
            }
            
        }else{
            return view('dashboard.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(sales $sales)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return $this->destroydata($sales);
            }elseif(auth()->user()->accesstype =='Renters'){
                return view('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->destroydata($sales);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->destroydata($sales);
            }
        }else{
            return view('dashboard.index');
        }
    }
}
