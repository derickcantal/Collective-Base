<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\branch;
use App\Models\cabinet;
use App\Models\RentalPayments;
use App\Models\Renter;
use App\Models\branchlist;
use App\Models\history_rental_payments;
use App\Models\user_login_log;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use \Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver; 

class TransactionRentalController extends Controller
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

    public function rentalpaymentrecords($branchid,$rentersid,$cabid)
    {
        $branch = branch::where('branchid',$branchid)->first();

        $renter = Renter::where('rentersid', $rentersid)->first();

        $cabinet = cabinet::where('cabid',$cabid)->first();

        $rentalpayments = RentalPayments::where('cabid',$cabinet->cabid)
                                        ->latest()
                                        ->paginate(12);

        return view('transaction.rental.rental-payment-records',['renter' => $renter])
                ->with(compact('cabinet'))
                ->with(compact('branch'))
                ->with(compact('rentalpayments'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function selectrentercabinet($branchid,$rentersid)
    {
        // dd($branchid,$rentersid);

        $branch = branch::where('branchid',$branchid)->first();

        $renter = Renter::where('rentersid', $rentersid)->first();

        $cabinets = cabinet::where('userid',$rentersid)
                            ->where('branchid',$branch->branchid)
                            ->paginate(10);

        // dd($branch,$renter,$cabinet);

        return view('transaction.rental.show-cabinet-list',['renter' => $renter])
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

        return view('transaction.rental.branchrenter',compact('renter'))
              ->with(['branch' => $branch])
              ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function searchbranchrenter(Request $request,$branchid)
    {
        $branch = branch::where('branchid', $branchid)->first();

        $renter = branchlist::leftJoin('renters', function($join) {
                $join->on('branchlist.userid','=','renters.rentersid' );
                    })
                    ->where('renters.accesstype', 'Renters')
                    ->where('branchlist.branchid', $branch->branchid)
                    ->where(function(Builder $builder) use($branch,$request){
                        $builder
                        ->where('renters.username','like',"%{$request->search}%")
                        ->orWhere('renters.firstname','like',"%{$request->search}%")
                        ->orWhere('renters.lastname','like',"%{$request->search}%")
                        ->orWhere('renters.email','like',"%{$request->search}%");
                        })
              ->paginate(10);

        return view('transaction.rental.branchrenter',compact('renter'))
              ->with(['branch' => $branch])
              ->with('i', (request()->input('page', 1) - 1) * 10);
    }
    
    public function loaddata(){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        
        $branch = branch::orderby('branchid')->paginate(10);

        $notes = 'Rental Payments';
        $status = 'Success';
        $this->userlog($notes,$status);

        return view('transaction.rental.index')->with(['branch' => $branch])
                                        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function storedata($request){
        
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

        $today = Carbon::now();
        $today->month;
        $today->year;

        $cabinet = cabinet::where('cabid', $request->cabinetname)->first();

        $renter = Renter::where('rentersid', $cabinet->userid)->first();

        $rentalpayment = RentalPayments::where('userid',$renter->rentersid)
                ->where(function(Builder $builder) use($request,$cabinet) {
                    $builder->where('cabid',$cabinet->cabid)
                        ->where('rpmonth',$request->rpmonth)
                        ->where('rpyear',$request->rpyear);
                    })
                    ->latest()->first();

        $rentalpaymenthistory = history_rental_payments::where('userid',$renter->rentersid)
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
        dd($totalbalance);


        if($rentalpayment)
        {
            
            if($rentalpayment->fully_paid == 'Y')
            {
                $notes = 'Rental Payments. Month/Year has been paid';
                $status = 'Failed';
                $this->userlog($notes,$status);

                return redirect()->back()
                                ->with('failed','Rental Month/Year has been paid.');
            }
            
        }       
        
        if($rentalpaymenthistory)
        {
            
            if($rentalpaymenthistory->fully_paid == 'Y')
            {
                $notes = 'Rental Payments. Month/Year has been paid';
                $status = 'Failed';
                $this->userlog($notes,$status);

                return redirect()->back()
                                ->with('failed','Rental Month/Year has been paid.');
            }
            
        }   

        if($request->paidamount <= 0)
        {
            $notes = 'Rental Payments. Total amount paid must not be equal 0.';
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->back()
                                ->with('failed','Total amount paid must not be equal 0.');
        }
        if($request->totalbalance < 0)
        {
            $notes = 'Rental Payments. Please pay exact amount.';
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->back()
                                ->with('failed','Please pay exact amount');
        }
        if($totalbalance == 0)
        {
            if($today->month == $cabinet->rpmonth && $today->year == $cabinet->rpyear){
                $fullypaid = 'Y';
            }else{
                $fullypaid = 'N';
            }
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
            'userid' => $renter->rentersid,
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

        $rentalpaymentupdate = RentalPayments::where('userid',$renter->rentersid)
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
                        $builder->where('userid',$renter->rentersid)
                                ->where('cabid',$cabinet->cabid)
                                ->where('rpmonth',$request->rpmonth)
                                ->where('rpyear',$request->rpyear)
                                ->where('fully_paid', 'N');
                    })->update([
                        'fully_paid' => "Y",
                    ]);
            
            if($today->month == $cabinet->rpmonth && $today->year == $cabinet->rpyear){
                $fp = 'Y';
                $cabinetupdate = cabinet::where('cabid',$cabinet->cabid)
                        ->update([
                            'fully_paid' => $fp,
                        ]);
            }else{
                $fp = 'N';
            }        
        }

        if($RentalPayments)
        {
            $notes = 'Rental Payments. Create.';
            $status = 'Success';
            $this->userlog($notes,$status);
           
            return redirect()->route('transactionrental.index')
                                ->with('success','Payment Successful.');
        }else{
            $notes = 'Rental Payments. Create.';
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('transactionrental.index')
                                ->with('failed','Payment Unsuccessful');
        }
    }

    public function updatedata($request,$rentalPayments){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $rent = Renter::where('username',$request->username)->first();
        $br = Renter::where('branchname',$request->branchname)->first();
        $cab = cabinet::where('cabinetname',$request->cabinetname)
        ->where(function(Builder $builder) use($request){
            $builder->where('branchname',$request->branchname);
        })->first();
        $rentalPayment = RentalPayments::findOrFail($rentalPayments);

        $mod = 0;
        $mod = $rentalPayment->mod;
        if($rentalPayment->status == 'Paid'){
            $notes = 'Rental Payments. Paid. Modification Not Allowed';
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('transactionrental.index')
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
                'userid' => $rent->rentersid,
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
                $notes = 'Rental Payments. Update.';
                $status = 'Success';
                $this->userlog($notes,$status);

                return redirect()->route('transactionrental.index')
                            ->with('success','Rental Payment updated successfully');
            }else{
                $notes = 'Rental Payments. Update.';
                $status = 'Failed';
                $this->userlog($notes,$status);

                return redirect()->route('transactionrental.index')
                            ->with('failed','Rental Payment updated failed');
            }
            
        }
    }

    public function destroydata($request,$rentalPayments){
        $notes = 'Rental Payments. Delete. Not Allowed.';
        $status = 'Failed';
        $this->userlog($notes,$status);
        return redirect()->route('transactionrental.index')
                            ->with('failed','Delete Not Allowed');
                            
        $rentalPayments->delete();
        
        $rentalPayments = RentalPayments::wherenot('accesstype', 'Renters')->get();
        if ($rentalPayments->isNotEmpty()) {
            
            return redirect()->route('transactionrental.index')
            ->with('success','Sales Requests deleted successfully');
        }
        else{
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect('/');
        }
    }

    public function search(Request $request)
    {
        $branch = branch::where('branchname','like',"%{$request->search}%")
                    ->orderBy('branchname',$request->orderrow)
                    ->paginate($request->pagerow);
        
            return view('transaction.rental.index',compact('branch'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
        
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
    

    public function create($branchid,$rentersid,$cabid)
    {
        $branch = branch::where('branchid',$branchid)->first();

        $renter = Renter::where('rentersid', $rentersid)->first();

        $cabinet = cabinet::where('cabid',$cabid)->first();

        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return view('transaction.rental.create')
                                ->with(['branch' => $branch])
                                ->with(['renter' => $renter])
                                ->with(['cabinet' => $cabinet]);   
            }elseif(auth()->user()->accesstype =='Administrator'){
                return view('transaction.rental.create')
                                ->with(['branch' => $branch])
                                ->with(['renter' => $renter])
                                ->with(['cabinet' => $cabinet]);   
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
        
    }

    public function store(Request $request)
    {

        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->storedata($request);         
            }elseif(auth()->user()->accesstype =='Administrator'){

                return $this->storedata($request); 
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
        
    }

    public function show($renters)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                $renters = Renter::findOrFail($renters);
                return view('transaction.rental.show',['$renters' => $renters]); 
            }elseif(auth()->user()->accesstype =='Administrator'){
                $renters = Renter::findOrFail($renters);
                return view('transaction.rental.show',['$renters' => $renters]);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
        
    }
    
    public function edit($rentalPayments)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                $rentalPayments = RentalPayments::findOrFail($rentalPayments);
                return view('transaction.rental.edit',['rentalPayments' => $rentalPayments]); 
            }elseif(auth()->user()->accesstype =='Administrator'){
                $rentalPayments = RentalPayments::findOrFail($rentalPayments);
        return view('transaction.rental.edit',['rentalPayments' => $rentalPayments]);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
        
    }

    public function update(Request $request, $rentalPayments)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->updatedata($request,$rentalPayments);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->updatedata($request,$rentalPayments);
            }
            
        }else{
            return redirect()->route('dashboardoverview.index');
        }
        
    }

    public function destroy(Request $request, RentalPayments $rentalPayments): RedirectResponse
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->destroydata($request,$rentalPayments);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->destroydata($request,$rentalPayments);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }
}
