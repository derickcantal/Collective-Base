<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;
use App\Models\cabinet;
use App\Models\branch;
use App\Models\Renters;
use App\Models\history_rental_payments;
use App\Models\RentalPayments;
use App\Models\rental_active_month;
use App\Models\user_login_log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class RenterCashierRentalController extends Controller
{
    public function search(Request $request)
    {
        $cabinets = cabinet::where('branchname',auth()->user()->branchname)
                    ->where(function(Builder $builder) use($request){
                        $builder->orWhere('cabinetname','like',"%{$request->search}%")
                                ->orWhere('userid','like',"%{$request->search}%")
                                ->orWhere('email','like',"%{$request->search}%")
                                ->orWhere('created_by','like',"%{$request->search}%")
                                ->orWhere('updated_by','like',"%{$request->search}%")
                                ->orWhere('status','like',"%{$request->search}%") 
                                
                                ->orderBy('branchname','asc')
                                ->orderBy('cabinetname','asc');
                    })
                    ->orderBy('cabid',$request->orderrow)
                    ->where('email','!=','Vacant')
                    ->paginate($request->pagerow);
    
        return view('rentercashierrental.index',compact('cabinets'))
            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');
        if(auth()->user()->accesstype == 'Cashier'){
            $cabinets = cabinet::where('branchname',auth()->user()->branchname)
                    ->where(function(Builder $builder){
                        $builder->where('email','!=','Vacant')
                            ->orderBy('status','asc')
                            ->orderBy('cabid','asc')
                            ->orderBy('branchname','asc');
                        })
                        ->paginate(5);
            
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
                'notes' => 'Renter. Cashier. Rental.',
                'status'  => 'Success',
            ]);
        
            return view('rentercashierrental.index',compact('cabinets'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
        }else{
            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $cabid)
    {
        if(auth()->user()->accesstype == 'Cashier'){
            $cabinet = cabinet::where('cabid', $cabid)->first();

            $renter = Renters::where('userid', $cabinet->userid)->first();

            $rentername = $renter->lastname . ', ' . $renter->firstname;

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
                    return redirect()->route('rentercashierrental.select',$cabid)
                                    ->with('failed','Rental Month/Year has been paid.');
                }
                
            } 
            if($rentalpaymenthistory)
            {
                if($rentalpaymenthistory->rpbal != 0)
                {
                    
                    return view('rentercashierrental.create')
                                    ->with(['cabinet' => $cabinet])
                                    ->with(['renters' => $renter])
                                    ->with('rpbal', $rpbal)
                                    ->with('rpmonth', $rpmonth)
                                    ->with('rpyear', $rpyear)
                                    ->with('rentername', $rentername);
                }
                if($rentalpaymenthistory->fully_paid == 'Y')
                {
                    return redirect()->route('rentercashierrental.select',$cabid)
                                    ->with('failed','Rental Month/Year has been paid.');
                }
                
            }   

            return view('rentercashierrental.create')
                                    ->with(['cabinet' => $cabinet])
                                    ->with(['renters' => $renter])
                                    ->with('rpbal', $rpbal)
                                    ->with('rpmonth', $rpmonth)
                                    ->with('rpyear', $rpyear)
                                    ->with('rentername', $rentername);
        }else{
            return redirect()->route('dashboard.index');
        }
    }

    public function select(Request $request,$cabid)
    {
        if(auth()->user()->accesstype == 'Cashier'){
            $cabinet = cabinet::where('cabid', $cabid)->first();

            $renter = Renters::where('userid', $cabinet->userid)->first();

            $rentername = $renter->lastname . ', ' . $renter->firstname;

                    
            
            return view('rentercashierrental.select')
                                    ->with(['cabinet' => $cabinet])
                                    ->with(['renters' => $renter])
                                    ->with('rentername', $rentername);
        }else{
            return redirect()->route('dashboard.index');
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { 
        if(auth()->user()->accesstype == 'Cashier'){
            $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');

            $cabinet = cabinet::where('cabid', $request->cabid)->first();

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
                        'notes' => 'Renter. Cashier. Rental. Create. Rental Month/Year has been paid.',
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
                        'notes' => 'Renter. Cashier. Rental. Create. Rental Month/Year has been paid.',
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
                    'notes' => 'Renter. Cashier. Rental. Create. Total amount paid must not be equal 0.',
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
                    'notes' => 'Renter. Cashier. Rental. Create. Please pay exact amount.',
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
                'branchid' => auth()->user()->branchid,
                'branchname' => auth()->user()->branchname,
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
                    'notes' => 'Renter. Cashier. Rental. Create',
                    'status'  => 'Success',
                ]);
                return redirect()->route('rentercashierrental.index')
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
                    'notes' => 'Renter. Cashier. Rental. Create',
                    'status'  => 'Failed',
                ]);
                return redirect()->route('rentercashierrental.index')
                                    ->with('failed','Payment Unsuccessful');
            }
        }else{
            return redirect()->route('dashboard.index');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if(auth()->user()->accesstype == 'Cashier'){
            $rentalpayments = RentalPayments::where('cabid',$id)
                        ->where(function(Builder $builder){
                            $builder->where('branchname',auth()->user()->branchname);
                        })->latest()->paginate(5);

            $rental = RentalPayments::where('cabid',$id)
                        ->where(function(Builder $builder){
                            $builder->where('branchname',auth()->user()->branchname);
                        })->first();

            $rentalpaymentshistory = history_rental_payments::where('cabid',$id)
                        ->where(function(Builder $builder){
                            $builder->where('branchname',auth()->user()->branchname);
                        })->latest()->paginate(5);

            $rentalhistory = history_rental_payments::where('cabid',$id)
                        ->where(function(Builder $builder){
                            $builder->where('branchname',auth()->user()->branchname);
                        })->first();

                    
            if(!empty($rental)){
                $fullname = $rental->lastname .', ' . $rental->firstname .' '. $rental->middlename;
                $cabn = $rental->cabinetname;

                return view('rentercashierrental.show')
                    ->with('i', (request()->input('page', 1) - 1) * 5)
                    ->with('fullname', $fullname)
                    ->with('cabn', $cabn)
                    ->with(['rentalpayments' => $rentalpayments]);
            }
            elseif(!empty($rentalhistory))
            {
                $fullname = $rentalhistory->lastname .', ' . $rentalhistory->firstname .' '. $rentalhistory->middlename;
                $cabn = $rentalhistory->cabinetname;

                return view('rentercashierrental.show')
                    ->with('i', (request()->input('page', 1) - 1) * 5)
                    ->with('fullname', $fullname)
                    ->with('cabn', $cabn)
                    ->with(['rentalpayments' => $rentalpaymentshistory]);
            }
            else
            {
                return redirect()->back()
                                    ->with('failed','No Records Found.');
            }
        }else{
            return redirect()->route('dashboard.index');
        }
        

        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('dashboard.index');
        if(auth()->user()->accesstype == 'Cashier'){
            $currentmonth = rental_active_month::query()->get();


            if(empty($currentmonth))
            {
                return redirect()->route('rentercashierrental.index')
                                    ->with('failed','No Records Found.');
            }
            else{
                dd('Record Found');
                    
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return redirect()->route('dashboard.index');
        if(auth()->user()->accesstype == 'Cashier'){

        }else{
            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return redirect()->route('dashboard.index');
        if(auth()->user()->accesstype == 'Cashier'){

        }else{
            return redirect()->route('dashboard.index');
        }
    }
}
