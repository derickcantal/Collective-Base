<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\branch;
use App\Models\cabinet;
use App\Models\RentalPayments;
use App\Models\Renter;
use App\Models\branchlist;
use App\Models\user_login_log;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use \Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;  

class TransactionRenterRemittanceController extends Controller
{
    public function userlog($notes,$status)
    {
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
    
    public function renterremittancerecords($branchid,$rentersid,$cabid)
    {
        $today = Carbon::now();
        $tmonth = $today->month;
        $tyear = $today->year;
        
        $daysno = Carbon::now()->month($tmonth)->daysInMonth;

        $branch = branch::where('branchid',$branchid)->first();

        $renter = Renter::where('rentersid', $rentersid)->first();

        $cabinet = cabinet::where('cabid',$cabid)->first();

        $rentalpayments = RentalPayments::where('cabid',$cabinet->cabid)
                                        ->latest()
                                        ->paginate(12);

        return view('transaction.remittance.rental-remittance-records',compact('renter'))
                ->with(compact('cabinet'))
                ->with(compact('branch'))
                ->with(compact('rentalpayments'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function selectrentercabinet($branchid,$rentersid)
    {
        $branch = branch::where('branchid',$branchid)->first();

        $renter = Renter::where('rentersid', $rentersid)->first();

        $cabinets = cabinet::where('userid',$rentersid)
                            ->where('branchid',$branch->branchid)
                            ->paginate(10);
        
        $cabinetcount = cabinet::where('userid',$rentersid)
                            ->where('branchid',$branch->branchid)
                            ->count();
        if($cabinetcount == 0)
        {
            return redirect()->back()
                                ->with('failed','No Cabinet Record Found in this branch.');
        }

        // dd($branch,$renter,$cabinet);

        return view('transaction.remittance.show-cabinet-list',['renter' => $renter])
                ->with(compact('cabinets')) 
                ->with(compact('branch'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function showbranchrenter($branchid)
    {
        $branch = branch::where('branchid', $branchid)->first();

        $renter = branchlist::leftJoin('renters', function($join) {
                $join->on('branchlist.userid','=','renters.rentersid' );
                    })
                    ->where(function(Builder $builder) use($branch){
                        $builder
                        ->where('renters.accesstype', 'Renters')
                        ->where('branchlist.branchid', $branch->branchid);
                        })
              ->paginate(10);

        return view('transaction.remittance.branchrenter',compact('renter'))
              ->with(['branch' => $branch])
              ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function searchbranchrenter(Request $request,$branchid)
    {
    
    }

    public function loaddata()
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        
        $branch = branch::orderby('branchid')->paginate(10);

        $notes = 'Rental Payments';
        $status = 'Success';
        $this->userlog($notes,$status);

        return view('transaction.remittance.index')->with(['branch' => $branch])
                                        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function storedata($request,$cabinetid)
    {

    }

    public function updatedata($request,$rentalPayments)
    {

    }

    public function destroydata($request,$rentalPayments)
    {

    }

    public function search(Request $request)
    {

    }

    public function index()
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->loaddata();
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->loaddata();
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

   public function createdetails(Request $request,$branchid,$rentersid,$cabid)
    {
        $branch = branch::where('branchid',$branchid)->first();

        $renter = Renter::where('rentersid', $rentersid)->first();

        $cabinet = cabinet::where('cabid',$cabid)->first();

        $rpmonth = $request->rpmonth;
        $rpyear = $request->rpyear;

        $rentalpaymenttotal = RentalPayments::where('userid',$renter->rentersid)
                ->where(function(Builder $builder) use($request,$cabinet) {
                    $builder->where('cabid',$cabinet->cabid)
                        ->where('rpmonth',$request->rpmonth)
                        ->where('rpyear',$request->rpyear);
                    })
                    ->sum('rpamount');

        $rentalpaymenthistorytotal = history_rental_payments::where('userid',$renter->rentersid)
                    ->where(function(Builder $builder) use($request,$cabinet) {
                        $builder->where('cabid',$cabinet->cabid)
                            ->where('rpmonth',$request->rpmonth)
                            ->where('rpyear',$request->rpyear);
                        })
                        ->sum('rpamount');

        $totalbalance = $cabinet->cabinetprice - ($rentalpaymenttotal + $rentalpaymenthistorytotal);
        
        if($totalbalance <= 0)
        {
            return redirect()->back()
                                ->with('failed','Selected Applicable Month Already Paid');
        }
        

        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return view('transaction.remittance.create-details')
                                ->with(['branch' => $branch])
                                ->with(['renter' => $renter])
                                ->with(['cabinet' => $cabinet])
                                ->with(['rpmonth' => $rpmonth])
                                ->with(['rpyear' => $rpyear])
                                ->with(['totalbalance' => $totalbalance]);   
            }elseif(auth()->user()->accesstype =='Administrator'){
                return view('transaction.remittance.create-details')
                                ->with(['branch' => $branch])
                                ->with(['renter' => $renter])
                                ->with(['cabinet' => $cabinet])
                                ->with(['rpmonth' => $rpmonth])
                                ->with(['rpyear' => $rpyear])
                                ->with(['totalbalance' => $totalbalance]);      
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }
    
    public function create($branchid,$rentersid,$cabid)
    {
        $branch = branch::where('branchid',$branchid)->first();

        $renter = Renter::where('rentersid', $rentersid)->first();

        $cabinet = cabinet::where('cabid',$cabid)->first();

        $lwstartweek = Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d') ;
        $lwendweek = Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d') ;
        $curstartweek = Carbon::now()->startOfWeek()->format('Y-m-d') ;
        $curendweek = Carbon::now()->startOfWeek()->addDays(5)->format('Y-m-d') ;

        // dd('Test Filter');

        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return view('transaction.remittance.create')
                                ->with(['branch' => $branch])
                                ->with(['renter' => $renter])
                                ->with(['cabinet' => $cabinet])
                                ->with(['lwstartweek' => $lwstartweek])  
                                ->with(['lwendweek' => $lwendweek]);   
            }elseif(auth()->user()->accesstype =='Administrator'){
                return view('transaction.remittance.create')
                                ->with(['branch' => $branch])
                                ->with(['renter' => $renter])
                                ->with(['cabinet' => $cabinet])
                                ->with(['lwstartweek' => $lwstartweek])  
                                ->with(['lwendweek' => $lwendweek]);   
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
