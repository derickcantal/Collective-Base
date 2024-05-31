<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Models\cabinet;
use App\Models\user_login_log;
use \Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class SalesController extends Controller
{
    public function userlog($notes,$status){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');
        
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
        $sales = Sales::latest()
                    ->paginate(5);

        $notes = 'Sales';
        $status = 'Success';
        $this->userlog($notes,$status);

        return view('sales.index',compact('sales'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function loaddata_cashier(){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');
        $sales = Sales::where('branchname',auth()->user()->branchname)
                        ->latest()
                        ->paginate(5);

        $notes = 'Sales';
        $status = 'Success';
        $this->userlog($notes,$status);

        return view('sales.index',compact('sales'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function storedata($request){
        
        $validated = $request->validate([
            'salesavatar'=>'required|image|file',
            'payavatar'=>'image|file',
        ]);
        if(empty($request->srp)){
            $notes = 'Sales. Create. Please update price. ' . $request->productname;
            $status = 'Failed';
            $this->userlog($notes,$status);

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
            $manager2 = ImageManager::imagick();
            $name_gen2 = hexdec(uniqid()).'.'.$request->file('payavatar')->getClientOriginalExtension();
            
            $image2 = $manager2->read($request->file('payavatar'));
        
            $encoded2 = $image2->toWebp()->save(storage_path('app/public/salespayavatar/'.$name_gen2.'.webp'));
            $path2 = 'salespayavatar/'.$name_gen2.'.webp';
            //$path2 = Storage::disk('public')->put('salespayavatar',$request->file('payavatar'));
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
            $notes = 'Sales. Create. ' . $request->productname;
            $status = 'Success';
            $this->userlog($notes,$status);

            return redirect()->route('sales.index')
                        ->with('success','Sales created successfully.');
        }else{
            $notes = 'Sales. Create. ' . $request->productname;
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('sales.index')
                        ->with('failed','Sales creation failed');
        }  
    }
    
    public function updatedata($request,$sales){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');
        $cabn = cabinet::where('cabid',$request->cabid)
                        ->where(function(Builder $builder) use($request){
                            $builder->where('branchname',$request->branchname);
                        })->first();

        $sales = Sales::findOrFail($sales);

        $item = $sales->productname;

        $oldavatar = $sales->salesavatar;

        $oldavatar1 = $sales->payavatar;


        $mod = 0;
        $mod = $sales->mod;

        $validated = $request->validate([
            'salesavatar'=>'image|file',
            'payavatar'=>'image|file',
        ]);

        if($sales->mod >= 3){
            $notes = 'Sales. Modification Limit Reach. ' . $item;
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('sales.index')
                            ->with('failed','Transaction completed. Modifications Not Allowed');
        }else{
            if($request->returned == 'N'){
                
                // $path = Storage::disk('public')->put('sales',$request->file('salesavatar'));
                if((!empty($request->salesavatar))){
                    $manager = ImageManager::imagick();
                    $name_gen = hexdec(uniqid()).'.'.$request->file('salesavatar')->getClientOriginalExtension();
                    
                    $image = $manager->read($request->file('salesavatar'));
                
                    $encoded = $image->toWebp()->save(storage_path('app/public/sales/'.$name_gen.'.webp'));
                    $path = 'sales/'.$name_gen.'.webp';

                    if($oldavatar = 'avatars/cash-default.jpg'){
                        
                    }else{  
                        Storage::disk('public')->delete($oldavatar);
                    }
                }else{
                    $path = $oldavatar;
                }

                if(!empty($request->payavatar)){
                    // $path2 = Storage::disk('public')->put('salespayavatar',$request->file('payavatar'));

                    $manager1 = ImageManager::imagick();
                    $name_gen1 = hexdec(uniqid()).'.'.$request->file('payavatar')->getClientOriginalExtension();
                    
                    $image1 = $manager1->read($request->file('payavatar'));
                
                    $encoded1 = $image1->toWebp()->save(storage_path('app/public/salespayavatar/'.$name_gen1.'.webp'));
                    $path1 = 'salespayavatar/'.$name_gen1.'.webp';

                    if($oldavatar1 = 'avatars/cash-default.jpg'){
                        
                    }else{  
                        Storage::disk('public')->delete($oldavatar1);
                    }
                }else{
                    $path1 = $oldavatar1;
                }
                
                if($request->payref == 'Null' or empty($request->payref) )
                {
                    $payref = 'Null';
                }else{
                    $payref = $request->payref;
                }

                if($request->snotes == 'Null' or empty($request->snotes) )
                {
                    $snotes = 'Null';
                }else{
                    $snotes = $request->snotes;
                }
                

                Sales::where('salesid', $sales->salesid)->update([
                    'salesavatar' => $path,
                    'productname' => $request->productname,
                    'qty' => $request->qty,
                    'srp' => $request->srp,
                    'total' => $request->qty * $request->srp,
                    'payavatar' => $path1,
                    'paytype' => $request->paytype,
                    'payref' => $payref,
                    'snotes' => $snotes,
                    'mod' => $mod + 1,
                    'updated_by' => auth()->user()->email,
                ]);
            }elseif($request->returned == 'Y'){
                if($request->snotes == 'Null'){
                    $notes = 'Sales. Notes must not be null. ' . $item;
                    $status = 'Failed';
                    $this->userlog($notes,$status);

                    return redirect()->back()
                        ->with('failed','Update Failed. Note must not be null');
                }elseif(empty($request->snotes)){
                    $notes = 'Sales. Note must not be empty. ' . $item;
                    $status = 'Failed';
                    $this->userlog($notes,$status);

                    return redirect()->back()
                    ->with('failed','Update Failed. Note must not be empty');
                }else{
                    Sales::where('salesid', $sales->salesid)->update([
                        'qty' => 0,
                        'total' => 0,
                        'snotes' => $request->qty . ' pc/s returned. ' . $request->snotes,
                        'returned' => 'Y',
                        'mod' => $mod + 1,
                        'updated_by' => auth()->user()->email,
                    ]);
                }
            }
            
            $notes = 'Sales. Update. ' . $item;
            $status = 'Success';
            $this->userlog($notes,$status);

            return redirect()->route('sales.index')
                            ->with('success','Sales Payment updated successfully');
        }
    }
    
    public function destroydata(){
        return redirect()->route('dashboard.index');
    }

    public function searchall($request)
    {
        if($request->orderrow == 'H-L'){
            $orderby = "total_sum";
            $orderrow = 'desc';
        }elseif($request->orderrow == 'L-H'){
            $orderby = "total_sum";
            $orderrow = 'asc';
        }elseif($request->orderrow == 'A-Z'){
            $orderby = "cabid";
            $orderrow = 'asc';
        }elseif($request->orderrow == 'Z-A'){
            $orderby = "cabid";
            $orderrow = 'desc';
        }

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
                    ->orWhere('snotes','like',"%{$request->search}%");
        })
        ->orderBy($orderby,$orderrow)
        ->paginate($request->pagerow);



        return view('sales.index',compact('sales'))
                ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
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
        ->paginate($request->pagerow);

        return view('sales.index',compact('sales'))
            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
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
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                $cabinet = cabinet::where('branchname',auth()->user()->branchname)->get();

                return view('sales.create',['cabinet' => $cabinet]);
            }
            else{
                return redirect()->route('dashboard.index');
            }
        }else{
            return redirect()->route('dashboard.index');
        }
        
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
        return redirect()->route('dashboard.index');
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                $sales = Sales::findOrFail($sales);
                return view('sales.show',['sales' => $sales]);
            }
            else{
                return redirect()->route('dashboard.index');
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($sales)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                $sales = Sales::findOrFail($sales);

                return view('sales.edit',['sales' => $sales]);       
            }elseif(auth()->user()->accesstype =='Administrator'){
                $sales = Sales::findOrFail($sales);

                return view('sales.edit',['sales' => $sales]);
            }
        }else{
            return redirect()->route('dashboard.index');
        }
        
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
        return redirect()->route('dashboard.index');
        
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
