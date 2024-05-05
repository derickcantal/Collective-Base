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

class RenterCashierRentalController extends Controller
{
    public function search(Request $request)
    {
        
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
                    ->first();

        if($rentalpayment)
        {
            if($rentalpayment->rpbal != 0)
            {
                return redirect()->route('rentercashierrental.select',$cabid)
                                ->with('failed','Existing balance!.');
            }
            if($rentalpayment->fully_paid == 'Y')
            {
                return redirect()->route('rentercashierrental.select',$cabid)
                                ->with('failed','Rental Month/Year has been paid.');
            }
            
        }  

        return view('rentercashierrental.create')
                                ->with(['cabinet' => $cabinet])
                                ->with(['renters' => $renter])
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

        $totalbalance = $cabinet->cabinetprice - $request->paidamount; 

        $rentalpayment = RentalPayments::where('userid',$renter->userid)
                ->where(function(Builder $builder) use($request,$cabinet) {
                    $builder->where('cabid',$cabinet->cabid)
                        ->where('rpmonth',$request->rpmonth)
                        ->where('rpyear',$request->rpyear);
                    })
                    ->first();

        if($rentalpayment)
        {
            if($rentalpayment->rpbal != 0)
            {
                return redirect()->route('rentercashierrental.index')
                                ->with('failed','Existing balance.');
            }
            if($rentalpayment->fully_paid == 'Y')
            {
                return redirect()->route('rentercashierrental.index')
                                ->with('failed','Rental Month/Year has been paid.');
            }
            
        }           

        if($request->paidamount <= 0)
        {
            return redirect()->route('rentercashierrental.index')
                                ->with('failed','Total amount paid must not be equal 0.');
        }
        if($totalbalance == 0)
        {
            $fullypaid = 'Y';
        }elseif($totalbalance > 0)
        {
            $fullypaid = 'N';
        }
        elseif($totalbalance < 0)
        {
            return redirect()->route('rentercashierrental.index')
                                ->with('failed','Please pay exact amount');
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
            $payavatar = $request->payavatar;
        }

        $RentalPayments = RentalPayments::create([
            'userid' => $renter->userid,
            'username' => $renter->username,
            'firstname' => $renter->firstname,
            'lastname' => $renter->lastname,
            'rpamount' => $request->paidamount,
            'rpbal' => $cabinet->cabinetprice - $request->paidamount,
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
        return view('rentercashierrental.show');
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
