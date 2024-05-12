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
        $cabinets = cabinet::where('branchname',auth()->user()->branchname)
                ->where(function(Builder $builder){
                    $builder->where('email','!=','Vacant')
                        ->orderBy('status','asc')
                        ->orderBy('cabid','asc')
                        ->orderBy('branchname','asc');
                    })
                    ->paginate(10);
    
        return view('rentercashierrental.index',compact('cabinets'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $cabid)
    {
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
    }

    public function select(Request $request,$cabid)
    {
        $cabinet = cabinet::where('cabid', $cabid)->first();

        $renter = Renters::where('userid', $cabinet->userid)->first();

        $rentername = $renter->lastname . ', ' . $renter->firstname;

                 
        
        return view('rentercashierrental.select')
                                ->with(['cabinet' => $cabinet])
                                ->with(['renters' => $renter])
                                ->with('rentername', $rentername);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { 
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
                return redirect()->back()
                                ->with('failed','Rental Month/Year has been paid.');
            }
            
        }       
        
        if($rentalpaymenthistory)
        {
            
            if($rentalpaymenthistory->fully_paid == 'Y')
            {
                return redirect()->back()
                                ->with('failed','Rental Month/Year has been paid.');
            }
            
        }   

        if($request->paidamount <= 0)
        {
            return redirect()->back()
                                ->with('failed','Total amount paid must not be equal 0.');
        }
        if($request->totalbalance < 0)
        {
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
           
        }

        if($RentalPayments)
        {
            return redirect()->route('rentercashierrental.index')
                                ->with('success','Payment Successful.');
        }else{
            return redirect()->route('rentercashierrental.index')
                                ->with('failed','Payment Unsuccessful');
        }
        

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
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
        

        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
        $currentmonth = rental_active_month::query()->get();


        if(empty($currentmonth))
        {
            return redirect()->route('rentercashierrental.index')
                                ->with('failed','No Records Found.');
        }
        else{
            dd('Record Found');
                
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
