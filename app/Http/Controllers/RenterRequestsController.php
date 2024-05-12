<?php

namespace App\Http\Controllers;

use App\Models\RenterRequests;
use Illuminate\Http\Request;
use App\Http\Requests\SalesRequestsSearchRequest;
use App\Models\Renters;
use App\Models\branch;
use App\Models\cabinet;
use App\Models\Sales;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use \Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;


class RenterRequestsController extends Controller
{

    public function loaddata(){
        $RenterRequests = RenterRequests::query()
                            ->orderBy('status','desc')
                            ->paginate(5);

            return view('rentersrequests.index',compact('RenterRequests'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function storedata($request){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');
        $rent = Renters::where('username',$request->username)->first();

        $br = branch::where('branchname',$request->branchname)->first();

        $cab = cabinet::where('cabinetname',$request->cabinetname)
        ->where(function(Builder $builder) use($request){
            $builder->where('branchname',$request->branchname);
        })->first();

        $RenterRequests = RenterRequests::create([
            'branchid' => $br->branchid,
            'branchname' => $request->branchname,
            'cabid' => $cab->cabid,
            'cabinetname' => $request->cabinetname,
            'totalsales' => $request->totalsales,
            'totalcollected' => $request->totalcollected,
            'avatarproof' => 'avatars/cash-default.jpg',
            'rnotes' => $request->rnotes,
            'userid' => $rent->userid,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'created_by' => Auth()->user()->email,
            'updated_by' => 'Null',
            'timerecorded' => $timenow,
            'mod' => 0,
            'posted' => 'N',
            'status' => 'Pending',
        ]);
    
        if ($RenterRequests) {
            //query successful
            return redirect()->route('rentersrequests.create')
                        ->with('success','Sales Request created successfully.');
        }else{
            return redirect()->route('rentersrequests.create')
                        ->with('success','Sales Request creation failed');
        }  
    }
    
    public function updatedata($request,$salerid){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');
        $validated = $request->validate([
            'avatarproof'=>'required|image|file',
        ]);

        $RenterRequests = RenterRequests::where('salesrid',$salerid)->first();

        $renter = Renters::where('userid',$RenterRequests->userid)->first();

        $manager2 = ImageManager::imagick();
        $name_gen2 = hexdec(uniqid()).'.'.$request->file('avatarproof')->getClientOriginalExtension();
        
        $image2 = $manager2->read($request->file('avatarproof'));
    
        $encoded = $image2->toWebp()->save(storage_path('app/public/rentersrequests/'.$name_gen2.'.webp'));
        $path = 'rentersrequests/'.$name_gen2.'.webp';

        // $path = Storage::disk('public')->put('rentersrequests',$request->file('avatarproof'));
        // $path = $request->file('avatar')->store('avatars','public');
        

        $renterrequestupdate = RenterRequests::where('salesrid', $salerid)->update([
                                    'totalcollected' => $RenterRequests->totalsales,
                                    'avatarproof' => $path,
                                    'rnotes' => $request->rnotes,
                                    'timerecorded_c' => $timenow,
                                    'updated_by' => Auth()->user()->email,
                                    'status' => 'Completed',
                                ]);

        if($renterrequestupdate)
        {
            if(auth()->user()->accesstype = 'Cashier'){
                return redirect()->route('dashboard.index')
                            ->with('success','Sales Request updated successfully');
            }elseif(auth()->user()->accesstype = 'Administrator' or auth()->user()->accesstype = 'Supervisor'){
                return redirect()->route('rentersrequests.index')
                            ->with('success','Sales Request updated successfully');
            }
        }
        else
        {
            if(auth()->user()->accesstype = 'Cashier'){
                return redirect()->route('dashboard.index')
                            ->with('failed','Request Process Failed');
            }elseif(auth()->user()->accesstype = 'Administrator' or auth()->user()->accesstype = 'Supervisor'){
                return redirect()->route('rentersrequests.index')
                            ->with('failed','Request Process Failed');
            }
            
        }
        
        
        
    }
    
    public function destroydata($request,$RenterRequests){
        $RenterRequests->delete();
        
        $RenterRequests = RenterRequests::wherenot('accesstype', 'Renters')->get();
        if ($RenterRequests->isNotEmpty()) {
            
            return redirect()->route('rentersrequests.index')
            ->with('success','Sales Requests deleted successfully');
        }
        else{
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect('/');
        }
    }

    public function displayall()
    {
        $RenterRequests = RenterRequests::all();
        return view('dashboard.index',['RenterRequests' => $RenterRequests]);
    }

    public function selectbranch(){
        $branches = branch::query()->paginate(5);;
        return view('rentersrequests.select-branch',['branches' => $branches])
                        ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    public function selectcabinet(Request $request,$branch){
        
        $branches = branch::query()->where('branchid',$branch)->first();
        $cabinet = cabinet::query()->where('branchname',$branches->branchname)->get();
        return view('rentersrequests.select-cabinet',['cabinet' => $cabinet])->with(['branchname'=> $branches->branchname]);
    }
    public function search(Request $request)
    {
        $RenterRequests = RenterRequests::orderBy('status','desc')
                    ->where(function(Builder $builder) use($request){
                        $builder->where('branchname','like',"%{$request->search}%")
                                ->orWhere('totalsales','like',"%{$request->search}%")
                                ->orWhere('totalcollected','like',"%{$request->search}%")
                                ->orWhere('rnotes','like',"%{$request->search}%")
                                ->orWhere('firstname','like',"%{$request->search}%")
                                ->orWhere('lastname','like',"%{$request->search}%")
                                ->orWhere('created_at','like',"%{$request->search}%")
                                ->orWhere('updated_by','like',"%{$request->search}%")
                                ->orWhere('status','like',"%{$request->search}%");
                    })
                    ->orderBy('lastname',$request->orderrow)
                    ->paginate($request->pagerow);
    
        return view('rentersrequests.index',compact('RenterRequests'))
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
    public function create($RenterRequests)
    {
        $cabinet = cabinet::all();
        $branch = branch::all();
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return view('rentersrequests.create',['cabinet' => $cabinet])
                                        ->with(['branch' => $branch]);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return view('rentersrequests.create',['cabinet' => $cabinet])
                                        ->with(['branch' => $branch]);
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
    public function show(RenterRequests $RenterRequests)
    {
        dd("Show Record");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($salerid)
    {
        
        $RenterRequests = RenterRequests::where('salesrid',$salerid)->first();

        $renter = Renters::where('userid',$RenterRequests->userid)->first();

        $fullname = $renter->lastname . ', ' . $renter->firstname . ' ' . $renter->middlename;

        return view('rentersrequests.edit')
                        ->with(['RenterRequests' => $RenterRequests])
                        ->with(['renter' => $renter])
                        ->with('fullname',$fullname);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $salerid)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return $this->updatedata($request,$salerid);
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->updatedata($request,$salerid);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->updatedata($request,$salerid);
            }
            
        }else{
            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, RenterRequests $RenterRequests): RedirectResponse
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->destroydata($request,$RenterRequests);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->destroydata($request, $RenterRequests);
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }
}
