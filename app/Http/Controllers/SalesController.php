<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Models\cabinet;
use \Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class SalesController extends Controller
{
    public function loaddata(){
        $sales = Sales::latest()
        ->paginate(5);

        return view('sales.index',compact('sales'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function loaddata_cashier(){
        $sales = Sales::where('branchname',auth()->user()->branchname)
        ->latest()
        ->paginate(5);

        return view('sales.index',compact('sales'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function storedata($request){
        
        $validated = $request->validate([
            'salesavatar'=>'required|image|file',
            'payavatar'=>'image|file',
        ]);
        if(empty($request->srp)){
            return redirect()->route('sales.index')
                        ->with('failed','Please update Price');
        }
        $manager = ImageManager::imagick();
        $name_gen = hexdec(uniqid()).'.'.$request->file('salesavatar')->getClientOriginalExtension();
        
        $image = $manager->read($request->file('salesavatar'));
       
        $encoded = $image->toWebp()->save(storage_path('app/public/sales/'.$name_gen.'.webp'));
        $path = 'sales/'.$name_gen.'.webp';
        
        //$path = Storage::disk('public')->put('sales',$request->file('salesavatar')); 
       
        $cabn = cabinet::where('cabinetname',$request->cabinetname)
        ->where(function(Builder $builder) use($request){
            $builder->where('branchname',$request->branchname);
        })->first();

        if(empty($request->snotes)){
            $snotes = 'Null';
        }else{
            $snotes = $request->snotes;
        }

        if(empty($request->payavatar)){
            $path2 = 'avatars/cash-default.jpg';
        }else{
            $path2 = Storage::disk('public')->put('salespayavatar',$request->file('payavatar'));
        }

        if(empty($request->payref)){
            $payref = 'Null';
        }else{
            $payref = $request->payref;
        }

        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');
                
        $sales = Sales::create([
            'salesavatar' => $path,
            'salesname' => 'Null',
            'cabid' => $cabn->cabid,
            'cabinetname' => $cabn->cabinetname,
            'productname' => $request->productname,
            'qty' => $request->qty,
            'origprice' => 0,
            'srp' => $request->srp,
            'total' => $request->qty * $request->srp,
            'grandtotal' => 0,
            'payavatar' => $path2,
            'paytype' => $request->paytype,
            'payref' => $payref,
            'userid' => $cabn->userid,
            'username' => $cabn->email,
            'accesstype' => auth()->user()->accesstype,
            'branchid' => $cabn->branchid,
            'branchname' => auth()->user()->branchname,
            'collected_status' => 'Pending',
            'returned' => 'N',
            'snotes' => $snotes,
            'posted' => 'N',
            'mod' => 0,
            'created_by' => auth()->user()->email,
            'updated_by' => 'Null',
            'timerecorded' => $timenow,
            'status' => 'Unposted',
        ]);
            
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

        $cabn = cabinet::where('cabinetname',$request->cabinetname)
        ->where(function(Builder $builder) use($request){
            $builder->where('branchname',$request->branchname);
        })->first();

        $sales = Sales::findOrFail($sales);
        $mod = 0;
        $mod = $sales->mod;
        if($sales->mod == '1'){
            return redirect()->route('sales.index')
                            ->with('failed','Transaction completed. Modifications Not Allowed');
        }elseif($sales->mod == '0'){
            if($request->returned == 'N'){
                $path = Storage::disk('public')->put('sales',$request->file('salesavatar'));
            
                $oldavatar = $sales->salesavatar;
                if(!empty($request->payavatar)){
                    $path2 = Storage::disk('public')->put('salespayavatar',$request->file('payavatar'));
                    $oldavatar2 = $sales->payavatar;
                }
                
                if($oldavatar == 'avatars/cash-default.jpg'){
                    
                }else{
                    Storage::disk('public')->delete($oldavatar);
                    if(!empty($request->payavatar)){
                        Storage::disk('public')->delete($oldavatar2);
                    }
                }

                Sales::where('salesid', $sales->salesid)->update([
                    'salesavatar' => $path,
                    'cabid' => $cabn->cabid,
                    'cabinetname' => $cabn->cabinetname,
                    'productname' => $request->productname,
                    'qty' => $request->qty,
                    'origprice' => 0,
                    'srp' => $request->srp,
                    'total' => $request->qty * $request->srp,
                    'grandtotal' => 0,
                    'payavatar' => $request->payavatar,
                    'paytype' => $request->paytype,
                    'payref' => $request->payref,
                    'userid' => $cabn->userid,
                    'username' => $cabn->email,
                    'accesstype' => auth()->user()->accesstype,
                    'branchid' => $cabn->branchid,
                    'branchname' => auth()->user()->branchname,
                    'collected_status' => 'Pending',
                    'returned' => 'N',
                    'snotes' => $request->snotes,
                    'posted' => 'N',
                    'mod' => $mod + 1,
                    'updated_by' => auth()->user()->email,
                    'status' => 'Unposted',
                ]);
            }elseif($request->returned == 'Y'){
                if($request->snotes == 'Null'){
                    return redirect()->route('sales.index')
                    ->with('failed','Update Failed. Note must not be null');
                }elseif(empty($request->snotes)){
                    return redirect()->route('sales.index')
                    ->with('failed','Update Failed. Note must not be empty');
                }else{
                    Sales::where('salesid', $sales->salesid)->update([
                        'total' => 0,
                        'snotes' => $request->snotes,
                        'returned' => 'Y',
                        'mod' => $mod + 1,
                        'updated_by' => auth()->user()->email,
                    ]);
                }
            }
            

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

    public function searchall($request)
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
                    ->latest();
        })
        ->paginate(5);

        return view('sales.index',compact('sales'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function searchbybranch($request)
    {
        $sales = Sales::where('branchname',auth()->user()->branchname)
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
                    ->latest();
        })
        ->paginate(5);

        return view('sales.index',compact('sales'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function search(Request $request)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return $this->searchbybranch($request);
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->searchall($request);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->searchall($request);
            }
        }else{
            return redirect()->route('dashboard.index');
        }
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
        $cabinet = cabinet::where('branchname',auth()->user()->branchname)->get();

        return view('sales.edit',['sales' => $sales])->with(['cabinet' => $cabinet]);
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
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->updatedata($request, $sales);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->updatedata($request, $sales);
            }
            
        }else{
            return redirect()->route('dashboard.index');
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
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->destroydata($sales);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->destroydata($sales);
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }
}
