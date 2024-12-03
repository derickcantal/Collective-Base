<?php

namespace App\Http\Controllers;

use App\Models\RenterRequests;
use Illuminate\Http\Request;
use App\Http\Requests\SalesRequestsSearchRequest;
use App\Models\Renter;
use App\Models\branch;
use App\Models\cabinet;
use App\Models\history_sales;
use App\Models\user_login_log;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use \Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;


class RenterRequestsController extends Controller
{
    public function userlog($notes,$status){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        
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
        $RenterRequests = RenterRequests::query()
                            ->orderBy('status','desc')
                            ->paginate(5);

        $notes = 'Renter Request';
        $status = 'Success';
        $this->userlog($notes,$status);

            return view('rentersrequests.index',compact('RenterRequests'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function storedata($request){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $rent = Renter::where('username',$request->username)->first();

        $br = branch::where('branchname',$request->branchname)->first();

        $cab = cabinet::where('cabinetname',$request->cabinetname)
        ->where(function(Builder $builder) use($request){
            $builder->where('branchname',$request->branchname);
        })->first();

        if(empty($request->rnotes)){
            $rnotes = 'Null';
        }else{
            $rnotes = $request->rnotes;
        }

        $RenterRequests = RenterRequests::create([
            'branchid' => $br->branchid,
            'branchname' => $request->branchname,
            'cabid' => $cab->cabid,
            'cabinetname' => $request->cabinetname,
            'totalsales' => $request->totalsales,
            'totalcollected' => $request->totalcollected,
            'avatarproof' => 'avatars/cash-default.jpg',
            'rnotes' => $rnotes,
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
            $notes = 'Renter Request. Create';
            $status = 'Success';
            $this->userlog($notes,$status);

            return redirect()->route('rentersrequests.create')
                        ->with('success','Sales Request created successfully.');
        }else{
            $notes = 'Renter Request. Create';
            $status = 'Failed';
            $this->userlog($notes,$status);
            
            return redirect()->route('rentersrequests.create')
                        ->with('success','Sales Request creation failed');
        }  
    }
    
    public function updatedata($request,$salerid){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

        $validated = $request->validate([
            'avatarproof'=>'required|image|file',
        ]);
        
        $RenterRequests = RenterRequests::where('salesrid',$salerid)->first();

        $renter = Renter::where('rentersid',$RenterRequests->userid)->first();
        $startdate = Carbon::parse($RenterRequests->rstartdate)->format('Y-m-d');
        $enddadte = Carbon::parse($RenterRequests->renddate)->format('Y-m-d');

        if($request->totalcollected > $RenterRequests->totalsales){

            $notes = 'Renter Request. Update. Total Collected must not be greater than total sales. ' . $renter->lastname;
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->back()
                            ->with('failed','Total Collected must not be greater than total sales');
        }
        if($RenterRequests->totalsales == 0){
            $notes = 'Renter Request. Update. Zero Sales. ' . $renter->lastname;
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('rentersrequests.index')
                            ->with('failed','Total Sales is 0.');
        }

        $manager2 = ImageManager::imagick();
        $name_gen2 = hexdec(uniqid()).'.'.$request->file('avatarproof')->getClientOriginalExtension();
        
        $image2 = $manager2->read($request->file('avatarproof'));
    
        $encoded = $image2->toWebp()->save(storage_path('app/public/rentersrequests/'.$name_gen2.'.webp'));
        $path = 'rentersrequests/'.$name_gen2.'.webp';

        $oldavatar = $RenterRequests->avatarproof;

        if($oldavatar == 'avatars/cash-default.jpg'){
        
        }
        elseif(!empty($oldavatar))
        {
            Storage::disk('public')->delete($oldavatar);
        }

        // $path = Storage::disk('public')->put('rentersrequests',$request->file('avatarproof'));
        // $path = $request->file('avatar')->store('avatars','public');
        if(empty($request->rnotes)){
            $rnotes = 'Null';
        }else{
            $rnotes = $request->rnotes;
        }

        if(empty($request->totalcollected) or $request->totalcollected == 0){
            $tcollected = $RenterRequests->totalsales;
        }else{
            $tcollected = $request->totalcollected;
        }

        $renterrequestupdate = RenterRequests::where('salesrid', $salerid)->update([
                                    'totalcollected' => $tcollected,
                                    'avatarproof' => $path,
                                    'rnotes' => $rnotes,
                                    'timerecorded_c' => $timenow,
                                    'updated_by' => Auth()->user()->email,
                                    'status' => 'Completed',
                                ]);

        $history_sales =  history_sales::where('cabid',$RenterRequests->cabid)
                                ->where(function(Builder $builder) use($startdate,$enddadte){
                                    $builder->whereBetween('timerecorded', [$startdate .' 00:00:00', $enddadte .' 23:59:59'])
                                            ->where('collected_status', "For Approval")
                                            ->where('total','!=', 0)
                                            ->where('returned', 'N');
                                })->update([
                                    'collected_status' => 'Completed',
                                    'updated_by' => auth()->user()->email,
                                ]);

        if($renterrequestupdate)
        {
            if(auth()->user()->accesstype = 'Cashier'){
                $notes = 'Renter Request. Update. ' . $renter->lastname;
                $status = 'Success';
                $this->userlog($notes,$status);
                
                return redirect()->route('dashboard.index')
                            ->with('success','Sales Request updated successfully');
            }elseif(auth()->user()->accesstype = 'Administrator' or auth()->user()->accesstype = 'Supervisor'){
                $notes = 'Renter Request. Update. ' . $renter->lastname;
                $status = 'Success';
                $this->userlog($notes,$status);

                return redirect()->route('rentersrequests.index')
                            ->with('success','Sales Request updated successfully');
            }
        }
        else
        {
            if(auth()->user()->accesstype = 'Cashier'){
                $notes = 'Renter Request. Update. ' . $renter->lastname;
                $status = 'Failed';
                $this->userlog($notes,$status);

                return redirect()->route('dashboard.index')
                            ->with('failed','Request Process Failed');
            }elseif(auth()->user()->accesstype = 'Administrator' or auth()->user()->accesstype = 'Supervisor'){
                $notes = 'Renter Request. Update. ' . $renter->lastname;
                $status = 'Failed';
                $this->userlog($notes,$status);
                
                return redirect()->route('rentersrequests.index')
                            ->with('failed','Request Process Failed');
            }
            
        }
        
        
        
    }
    
    public function destroydata($request,$RenterRequests){
        return redirect()->route('dashboard.index');
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
                                ->orWhere('timerecorded','like',"%{$request->search}%")
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
     * Show the form for editing the specified resource.
     */
    public function edit($salerid)
    {
        $RenterRequests = RenterRequests::where('salesrid',$salerid)->first();

        $renter = Renter::where('rentersid',$RenterRequests->userid)->first();

        $fullname = $renter->lastname . ', ' . $renter->firstname . ' ' . $renter->middlename;

        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return view('rentersrequests.edit')
                                ->with(['RenterRequests' => $RenterRequests])
                                ->with(['renter' => $renter])
                                ->with('fullname',$fullname);    
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return view('rentersrequests.edit')
                                ->with(['RenterRequests' => $RenterRequests])
                                ->with(['renter' => $renter])
                                ->with('fullname',$fullname);       
            }elseif(auth()->user()->accesstype =='Administrator'){
                return view('rentersrequests.edit')
                                ->with(['RenterRequests' => $RenterRequests])
                                ->with(['renter' => $renter])
                                ->with('fullname',$fullname);       
            }
        }else{
            return redirect()->route('dashboard.index');
        }
        
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
