<?php

namespace App\Http\Controllers;

use App\Models\branch;
use App\Models\cabinet;
use App\Models\RentalPayments;
use App\Models\Renters;
use App\Models\history_rental_payments;
use App\Models\user_login_log;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use \Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class RentalPaymentsController extends Controller
{
    public function setpayment(){

        $cabinet = cabinet::latest()->first();

        return view('rentalpayments.create-set-payment')
                            ->with(['cabinet' => $cabinet]);
    }
    
    public function storesetpayment(Request $request){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');
        $cabinet = cabinet::query()->update([
                'rpmonth' => $request->rpmonth,
                'rpyear' => $request->rpyear,
                ]);
        
        if($cabinet){
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
                'notes' => 'Rental Payments. Set Payment Month',
                'status'  => 'Success',
            ]);  

            return redirect()->route('rentalpayments.index')
                ->with('success','Month Set Successful.');
        }else{
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
                'notes' => 'Rental Payments. Set Payment Month',
                'status'  => 'Failed',
            ]); 
            return redirect()->route('rentalpayments.index')
                ->with('failed','Month Set Failed.');
        }
        
    }

    public function loaddata(){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');
        $rentalPayments = RentalPayments::orderBy('status','desc')
                ->where(function(Builder $builder)  {
                    $builder->orderBy('branchname','asc')
                            ->orderBy('lastname','asc')
                            ->orderBy('cabinetname','asc');
                    })
                    ->latest()->paginate(5);

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
                        'notes' => 'Rental Payments',
                        'status'  => 'Success',
                    ]); 

                return view('rentalpayments.index')->with(['rentalPayments' => $rentalPayments])
                                            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function storedata($request){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');

        $cabinet = cabinet::where('cabid', $request->cabinetname)->first();

        $renter = Renters::where('userid', $cabinet->userid)->first();

        $rentalpayment = RentalPayments::where('userid',$renter->userid)
                ->where(function(Builder $builder) use($request,$cabinet) {
                    $builder->where('cabid',$cabinet->cabid)
                        ->where('rpmonth',$request->rpmonth)
                        ->where('rpyear',$request->rpyear);
                    })
                    ->latest()->first();

        $rentalpaymenthistory = history_rental_payments::where('userid',$renter->userid)
                    ->where(function(Builder $builder) use($request,$cabinet) {
                        $builder->where('cabid',$cabinet->cabid)
                            ->where('rpmonth',$request->rpmonth)
                            ->where('rpyear',$request->rpyear);
                        })
                        ->latest()->first();

        if(empty($rentalpayment))
        {
            $totalbalance = $cabinet->cabinetprice - $request->paidamount; 
        }
        else{
            $totalbalance = $rentalpayment->rpbal - $request->paidamount; 
        }


        if($rentalpayment)
        {
            
            if($rentalpayment->fully_paid == 'Y')
            {
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
                    'notes' => 'Rental Payments. Month/Year has been paid',
                    'status'  => 'Failed',
                ]); 

                return redirect()->back()
                                ->with('failed','Rental Month/Year has been paid.');
            }
            
        }       
        
        if($rentalpaymenthistory)
        {
            
            if($rentalpaymenthistory->fully_paid == 'Y')
            {
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
                    'notes' => 'Rental Payments. Month/Year has been paid',
                    'status'  => 'Failed',
                ]); 

                return redirect()->back()
                                ->with('failed','Rental Month/Year has been paid.');
            }
            
        }   

        if($request->paidamount <= 0)
        {
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
                'notes' => 'Rental Payments. Total amount paid must not be equal 0.',
                'status'  => 'Failed',
            ]); 

            return redirect()->back()
                                ->with('failed','Total amount paid must not be equal 0.');
        }
        if($request->totalbalance < 0)
        {
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
                'notes' => 'Rental Payments. Please pay exact amount.',
                'status'  => 'Failed',
            ]); 

            return redirect()->back()
                                ->with('failed','Please pay exact amount');
        }
        if($totalbalance == 0)
        {
            $fullypaid = 'Y';
        }
        elseif($totalbalance > 0)
        {
            $fullypaid = 'N';
        }
        

        if(empty($request->rpnotes))
        {
            $rpnotes = 'Null';
        }else
        {
            $rpnotes = $request->rpnotes;
        }
        if(empty($request->payavatar))
        {
            $payavatar = 'avatars/cash-default.jpg';
        }else
        {
            $validated = $request->validate([
                'payavatar'=>'image|file',
            ]);

            $manager2 = ImageManager::imagick();
            $name_gen2 = hexdec(uniqid()).'.'.$request->file('payavatar')->getClientOriginalExtension();
            
            $image2 = $manager2->read($request->file('payavatar'));
        
            $encoded = $image2->toWebp()->save(storage_path('app/public/rentalpayments/'.$name_gen2.'.webp'));
            $payavatar = 'rentalpayments/'.$name_gen2.'.webp';
        }


        $RentalPayments = RentalPayments::create([
            'userid' => $renter->userid,
            'username' => $renter->username,
            'firstname' => $renter->firstname,
            'lastname' => $renter->lastname,
            'rpamount' => $request->paidamount,
            'rpbal' => $totalbalance,
            'rppaytype' => $request->rppaytype,
            'rpmonth' => $request->rpmonth,
            'rpyear' => $request->rpyear,
            'rpnotes' => $rpnotes,
            'branchid' => $renter->branchid,
            'branchname' => $renter->branchname,
            'cabid' => $cabinet->cabid,
            'cabinetname' => $cabinet->cabinetname,
            'avatarproof' => $payavatar,
            'created_by' => auth()->user()->email,
            'updated_by' => 'Null',
            'timerecorded'  => $timenow,
            'posted'  => 'N',
            'fully_paid' => $fullypaid,
            'mod' => 0,
            'status' => 'Active',
        ]);

        $cabinetupdate = cabinet::where('cabid',$cabinet->cabid)
                    ->update([
                        'fully_paid' => $fullypaid,
                    ]);

        $rentalpaymentupdate = RentalPayments::where('userid',$renter->userid)
                ->where(function(Builder $builder) use($request,$cabinet) {
                    $builder->where('cabid',$cabinet->cabid)
                        ->where('rpmonth',$request->rpmonth)
                        ->where('rpyear',$request->rpyear);
                    })
                    ->latest()->first();
        
        if($rentalpaymentupdate->fully_paid == 'Y')
        {
            $rpu = RentalPayments::where('branchname',auth()->user()->branchname)
                    ->where(function(Builder $builder)use($request,$cabinet,$renter){
                        $builder->where('userid',$renter->userid)
                                ->where('cabid',$cabinet->cabid)
                                ->where('rpmonth',$request->rpmonth)
                                ->where('rpyear',$request->rpyear)
                                ->where('fully_paid', 'N');
                    })->update([
                        'fully_paid' => "Y",
                    ]);
            
            $cabinetupdate = cabinet::where('cabid',$cabinet->cabid)
                    ->update([
                        'fully_paid' => "Y",
                    ]);
            
        }

        if($RentalPayments)
        {
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
                'notes' => 'Rental Payments. Create',
                'status'  => 'Success',
            ]); 
            return redirect()->route('rentalpayments.index')
                                ->with('success','Payment Successful.');
        }else{
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
                'notes' => 'Rental Payments. Create',
                'status'  => 'Success',
            ]); 
            return redirect()->route('rentalpayments.index')
                                ->with('failed','Payment Unsuccessful');
        }
    }

    public function updatedata($request,$rentalPayments){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');
        $rent = Renters::where('username',$request->username)->first();
        $br = Renters::where('branchname',$request->branchname)->first();
        $cab = cabinet::where('cabinetname',$request->cabinetname)
        ->where(function(Builder $builder) use($request){
            $builder->where('branchname',$request->branchname);
        })->first();
        $rentalPayment = RentalPayments::findOrFail($rentalPayments);

        $mod = 0;
        $mod = $rentalPayment->mod;
        if($rentalPayment->status == 'Paid'){
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
                'notes' => 'Rental Payments. Paid. Modification Not Allowed' ,
                'status'  => 'Failed',
            ]); 
            return redirect()->route('rentalpayments.index')
                            ->with('failed','Already Paid. Modifications Not Allowed');
        }elseif($rentalPayment->status == 'Unpaid'){
            $path = Storage::disk('public')->put('rentalPayments',$request->file('avatarproof'));
            // $path = $request->file('avatar')->store('avatars','public');
            
            $oldavatar = $rentalPayment->avatarproof;
            
            if($oldavatar == 'avatars/cash-default.jpg'){
                
            }else{
                Storage::disk('public')->delete($oldavatar);
            }
        

            $rpayment = RentalPayments::where('rpid', $rentalPayments)->update([
                'userid' => $rent->userid,
                'username' => $request->username,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'rpamount' => $request->rpamount,
                'rppaytype' => $request->rppaytype,
                'rpmonth' => $request->rpmonth,
                'rpyear' => $request->rpyear,
                'rpnotes' => $request->rpnotes,
                'branchid' => $br->branchid,
                'branchname' => $request->branchname,
                'cabid' => $cab->cabid,
                'cabinetname' => $request->cabinetname,
                'avatarproof' => $path,
                'updated_by' => Auth()->user()->email,
                'mod' => $mod + 1,
                'status' => 'Paid',
            ]);
            if($rpayment){
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
                    'notes' => 'Rental Payments. Update',
                    'status'  => 'Success',
                ]); 

                return redirect()->route('rentalpayments.index')
                            ->with('success','Rental Payment updated successfully');
            }else{
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
                    'notes' => 'Rental Payments. Update',
                    'status'  => 'Failed',
                ]); 

                return redirect()->route('rentalpayments.index')
                            ->with('failed','Rental Payment updated failed');
            }
            
        }
    }

    public function destroydata($request,$rentalPayments){
        return redirect()->route('rentalpayments.index')
                            ->with('failed','Delete Not Allowed');
                            
        $rentalPayments->delete();
        
        $rentalPayments = RentalPayments::wherenot('accesstype', 'Renters')->get();
        if ($rentalPayments->isNotEmpty()) {
            
            return redirect()->route('rentalpayments.index')
            ->with('success','Sales Requests deleted successfully');
        }
        else{
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect('/');
        }
    }
    public function searchrenter(Request $request)
    {
        $pagerow = $request->pagerow;


        $renter = Renters::where('accesstype',"Renters")->orderBy('status','asc')
                    ->where(function(Builder $builder) use($request){
                        $builder
                                ->where('username','like',"%{$request->searchrbc}%")
                                ->orWhere('firstname','like',"%{$request->searchrbc}%")
                                ->orWhere('lastname','like',"%{$request->searchrbc}%")
                                ->orWhere('middlename','like',"%{$request->searchrbc}%")
                                ->orWhere('branchname','like',"%{$request->searchrbc}%")
                                ->orWhere('cabinetname','like',"%{$request->searchrbc}%")
                                ->orWhere('email','like',"%{$request->searchrbc}%")
                                ->orWhere('status','like',"%{$request->searchrbc}%");
                    })
                    ->orderBy('lastname',$request->orderrow)
                    ->paginate($pagerow);

                    return view('rentalpayments.create-select-renter',compact('renter'))
            ->with('i', (request()->input('page', 1) - 1) * $pagerow);
    }
    public function selectrenter()
    {
        $renter = Renters::where('accesstype',"Renters")
                    ->orderBy('status','asc')
                    ->paginate(5);
    
        return view('rentalpayments.create-select-renter',compact('renter'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
            
    }

    public function selectcabinet($renters)
    {
        $renter = Renters::findOrFail($renters);

        $cabinet = cabinet::where('userid', $renter->userid)->get();

        return view('rentalpayments.create-select-cabinet')
                    ->with(['renters' => $renter])
                    ->with(['cabinet' => $cabinet]);
    }

    public function selectpayment(Request $request)
    {
        if($request->cabinetname == 'SelectCabinet'){
            return redirect()->route('rentalpayments.selectcabinet',$request->userid)
                                ->with('failed','No Active Cabinet Selected.');
        }
        $renter = Renters::where('userid',$request->userid)->first();

        $cabinet = cabinet::where('cabid', $request->cabinetname)->first();

        $rentername = $renter->lastname . ', ' . $renter->firstname;

        $rentalpayment = RentalPayments::where('userid',$request->userid)
                ->where(function(Builder $builder) use($request) {
                    $builder->where('cabid',$request->cabinetname)
                        ->where('rpmonth',$request->rpmonth)
                        ->where('rpyear',$request->rpyear);
                    })
                    ->latest()->first();

        $rentalpaymenthistory = history_rental_payments::where('userid',$request->userid)
                    ->where(function(Builder $builder) use($request) {
                        $builder->where('cabid',$request->cabinetname)
                            ->where('rpmonth',$request->rpmonth)
                            ->where('rpyear',$request->rpyear);
                        })
                        ->latest()->first();
        
       
        if(!empty($rentalpayment))
        {
            $rpbal = $rentalpayment->rpbal;
        }
        elseif(!empty($rentalpaymenthistory))
        {
            $rpbal = $rentalpaymenthistory->rpbal;
        }
        else
        {
            $rpbal = $cabinet->cabinetprice;
        }
              
        $rpmonth = $request->rpmonth;
        $rpyear = $request->rpyear;

        if($rentalpayment)
        {
            if($rentalpayment->rpbal != 0)
            {
                
                return view('rentercashierrental.create')
                                ->with(['cabinet' => $cabinet])
                                ->with(['renters' => $renter])
                                ->with('rpbal', $rpbal)
                                ->with('rpmonth', $rpmonth)
                                ->with('rpyear', $rpyear)
                                ->with('rentername', $rentername);
            }
            if($rentalpayment->fully_paid == 'Y')
            {
                return redirect()->route('rentalpayments.selectcabinet',$request->userid)
                                ->with('failed','Rental Month/Year has been paid.');
            }
            
        } 
        if($rentalpaymenthistory)
        {
            if($rentalpaymenthistory->rpbal != 0)
            {
                
                return view('rentalpayments.create-select-payment')
                                ->with(['cabinet' => $cabinet])
                                ->with(['renters' => $renter])
                                ->with('rpbal', $rpbal)
                                ->with('rpmonth', $rpmonth)
                                ->with('rpyear', $rpyear)
                                ->with('rentername', $rentername);
            }
            if($rentalpaymenthistory->fully_paid == 'Y')
            {
                return redirect()->route('rentalpayments.selectcabinet',$request->userid)
                                ->with('failed','Rental Month/Year has been paid.');
            }
            
        } 
        return view('rentalpayments.create-select-payment')
                    ->with(['renters' => $renter])
                    ->with(['cabinet' => $cabinet])
                    ->with('rpbal', $rpbal)
                    ->with('rpmonth', $rpmonth)
                    ->with('rpyear', $rpyear)
                    ->with('rentername', $rentername);
    }

    public function search(Request $request)
    {
        $rentalPayments = RentalPayments::orderBy('status','desc')
                        ->where(function(Builder $builder) use($request){
                            $builder->where('branchname','like',"%{$request->search}%")
                                    ->orWhere('cabinetname','like',"%{$request->search}%")
                                    ->orWhere('rpamount','like',"%{$request->search}%")
                                    ->orWhere('rppaytype','like',"%{$request->search}%")
                                    ->orWhere('rpmonth','like',"%{$request->search}%")
                                    ->orWhere('rpyear','like',"%{$request->search}%")
                                    ->orWhere('rpnotes','like',"%{$request->search}%")
                                    ->orWhere('firstname','like',"%{$request->search}%")
                                    ->orWhere('lastname','like',"%{$request->search}%")
                                    ->orWhere('created_at','like',"%{$request->search}%")
                                    ->orWhere('updated_by','like',"%{$request->search}%")
                                    ->orWhere('status','like',"%{$request->search}%")
                                    ->orderBy('branchname','asc')
                                    ->orderBy('lastname','asc')
                                    ->orderBy('cabinetname','asc')
                                    ;
                        })
                        ->paginate(5);
        
            return view('rentalpayments.index',compact('rentalPayments'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
        
    }
    /**
     * Display a listing of the resource.
     */
    
    public function index()
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
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return view('rentalpayments.create');   
            }elseif(auth()->user()->accesstype =='Administrator'){
                return view('rentalpayments.create');
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
    public function show($renters)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                $renters = Renters::findOrFail($renters);
                return view('rentalpayments.show',['$renters' => $renters]); 
            }elseif(auth()->user()->accesstype =='Administrator'){
                $renters = Renters::findOrFail($renters);
                return view('rentalpayments.show',['$renters' => $renters]);
            }
        }else{
            return redirect()->route('dashboard.index');
        }
        
    }
    
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($rentalPayments)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                $rentalPayments = RentalPayments::findOrFail($rentalPayments);
                return view('rentalpayments.edit',['rentalPayments' => $rentalPayments]); 
            }elseif(auth()->user()->accesstype =='Administrator'){
                $rentalPayments = RentalPayments::findOrFail($rentalPayments);
        return view('rentalpayments.edit',['rentalPayments' => $rentalPayments]);
            }
        }else{
            return redirect()->route('dashboard.index');
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $rentalPayments)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->updatedata($request,$rentalPayments);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->updatedata($request,$rentalPayments);
            }
            
        }else{
            return redirect()->route('dashboard.index');
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, RentalPayments $rentalPayments): RedirectResponse
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->destroydata($request,$rentalPayments);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->destroydata($request,$rentalPayments);
            }
        }else{
            return redirect()->route('dashboard.index');
        }
           
        
    }
}
