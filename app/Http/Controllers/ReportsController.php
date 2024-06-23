<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Models\history_sales;
use App\Models\history_sales_requests;
use App\Models\history_attendance;
use App\Models\history_rental_payments;
use App\Models\user_login_log;
use \Carbon\Carbon;
use App\Models\branch;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ReportsController extends Controller
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
    public function searchtopsalesbranch(Request $request){
        
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
        

        if(empty($request->startdate) && empty($request->enddate)){
            if(empty($request->branchname) or $request->branchname == 'All'){
                $sales = history_sales::groupBy('cabid','cabinetname','branchname')
                ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
                ->orderBy($orderby,$orderrow)
                ->paginate($request->pagerow);

                $salesget = history_sales::groupBy('cabid','cabinetname','branchname')
                ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
                ->orderBy($orderby,$orderrow)
                ->get();
            }elseif(!empty($request->branchname)){
                $sales = history_sales::groupBy('cabid','cabinetname','branchname')
                ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
                ->where('branchname', $request->branchname)
                ->orderBy($orderby,$orderrow)
                ->paginate($request->pagerow);

                $salesget = history_sales::groupBy('cabid','cabinetname','branchname')
                ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
                ->where('branchname', $request->branchname)
                ->orderBy($orderby,$orderrow)
                ->get();
            }
            
        }elseif(empty($request->startdate) or empty($request->enddate)){
            $sales = history_sales::groupBy('cabid','cabinetname','branchname')
            ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
            ->orderBy($orderby,$orderrow)
            ->paginate($request->pagerow);

            $salesget = history_sales::groupBy('cabid','cabinetname','branchname')
            ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
            ->orderBy($orderby,$orderrow)
            ->get();
        }elseif(!empty($request->startdate) or !empty($request->enddate)){
            $startDate = Carbon::parse($request->startdate)->format('Y-m-d');
            $endDate = Carbon::parse($request->enddate)->format('Y-m-d');
            
            if(auth()->user()->accesstype == 'Cashier'){
            
            }elseif(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype == 'Supervisor'){
                if(empty($request->branchname) or $request->branchname == 'All'){
                    $sales = history_sales::groupBy('cabid','cabinetname','branchname')
                    ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
                    ->whereBetween('created_at', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                    ->orderBy($orderby,$orderrow)
                    ->paginate($request->pagerow);
        
                    $salesget = history_sales::groupBy('cabid','cabinetname','branchname')
                    ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
                    ->whereBetween('created_at', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                    ->orderBy($orderby,$orderrow)
                    ->get();
                }elseif(!empty($request->branchname)){
                    $sales = history_sales::groupBy('cabid','cabinetname','branchname')
                    ->where('branchname', $request->branchname)
                    ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
                    ->whereBetween('created_at', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                    ->orderBy($orderby,$orderrow)
                    ->paginate($request->pagerow);
        
                    $salesget = history_sales::groupBy('cabid','cabinetname','branchname')
                    ->where('branchname', $request->branchname)
                    ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
                    ->whereBetween('created_at', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                    ->orderBy($orderby,$orderrow)
                    ->get();
                }
                
            }
        }

        if(auth()->user()->accesstype == 'Cashier'){
            
        }elseif(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype == 'Supervisor'){
            
        }

        $branch = branch::orderBy('branchname', 'asc')->get();
        if($salesget)
        {
            $totalqty = collect($salesget)->sum('qty_sum');
            $totalsales = collect($salesget)->sum('total_sum');
        }
        

        return view('reports.top-sales-branch')->with(['sales' => $sales])
                                    ->with(['totalsales' => $totalsales])
                                    ->with(['totalqty' => $totalqty])
                                    ->with(['branch' => $branch])
                                    ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
       

    }

    public function topsalesbranch(){

        if(auth()->user()->accesstype == 'Cashier'){
            return redirect()->route('dashboard.index');
        }elseif(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype == 'Supervisor'){
            $sales = history_sales::groupBy('cabid','cabinetname','branchname')
            ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
            ->orderBy('total_sum','desc')
            ->paginate(10);

            $salesget = history_sales::groupBy('cabid','cabinetname','branchname')
            ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
            ->orderBy('total_sum','desc')
            ->get();
        }
        $branch = branch::orderBy('branchname', 'asc')->get();

        $totalqty = collect($salesget)->sum('qty_sum');
        $totalsales = collect($salesget)->sum('total_sum');

        return view('reports.top-sales-branch')->with(['sales' => $sales])
                                    ->with(['totalsales' => $totalsales])
                                    ->with(['totalqty' => $totalqty])
                                    ->with(['branch' => $branch])
                                    ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function searchhsales(Request $request)
    {  
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return $this->cashiersearch($request);  
            }elseif(auth()->user()->accesstype =='Renters'){
                return $this->rentersearch($request);
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->adminsearch($request);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->adminsearch($request);
            }
        }else{
            return redirect()->route('dashboard.index');
        }

    }

    public function cashiersearch($request){
        if($request->orderrow == 'H-L'){
            $orderby = "total";
            $orderrow = 'desc';
        }elseif($request->orderrow == 'L-H'){
            $orderby = "total";
            $orderrow = 'asc';
        }elseif($request->orderrow == 'A-Z'){
            $orderby = "productname";
            $orderrow = 'asc';
        }elseif($request->orderrow == 'Z-A'){
            $orderby = "productname";
            $orderrow = 'desc';
        }elseif($request->orderrow == 'Latest'){
            $orderby = "salesid";
            $orderrow = 'desc';
        }elseif($request->orderrow == 'Oldest'){
            $orderby = "salesid";
            $orderrow = 'asc';
        }
        
        if(empty($request->search)){
            if(empty($request->startdate) && empty($request->enddate)){
                $salesget = history_sales::where('branchname',auth()->user()->branchname)
                                        ->orderBy($orderby,$orderrow)
                                        ->get();
                $sales = history_sales::where('branchname',auth()->user()->branchname)
                                        ->orderBy($orderby,$orderrow)
                                        ->paginate($request->pagerow);

                $totalqty = collect($salesget)->sum('qty');
                $totalsales = collect($salesget)->sum('total');

                $branch = branch::orderBy('branchname', 'asc')->get();
    
                $sales_requests = history_sales_requests::where('status','Completed')->orderBy('status','desc')->paginate(5);
                
                $rentalpayments = history_rental_payments::where('branchname',auth()->user()->branchname)->orderBy('status','desc')->paginate(5);
    
                $attendance = history_attendance::where('branchname',auth()->user()->branchname)->latest()->paginate(5); 
                
    
                return view('reports.index')->with(['sales' => $sales])
                    ->with(['sales_requests' => $sales_requests])
                    ->with(['attendance' => $attendance])
                    ->with(['rentalpayments' => $rentalpayments])
                    ->with(['totalsales' => $totalsales])
                    ->with(['totalqty' => $totalqty])
                    ->with(['branch' => $branch]);
            }
            elseif(empty($request->startdate) or empty($request->enddate)){
                
                return redirect()->back()
                    
                    ->with('failed','Start & End Dates Required');
            }
            else{
                $startDate = Carbon::parse($request->startdate)->format('Y-m-d');
                $endDate = Carbon::parse($request->enddate)->format('Y-m-d');

                $salesget = history_sales::where('branchname',auth()->user()->branchname)
                                            ->whereBetween('created_at', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                                            ->orderBy($orderby,$orderrow)
                                            ->get();
                $sales = history_sales::where('branchname',auth()->user()->branchname)
                                        ->whereBetween('created_at', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                                        ->orderBy($orderby,$orderrow)
                                        ->paginate($request->pagerow);
                
                $totalqty = collect($salesget)->sum('qty');
                $totalsales = collect($salesget)->sum('total');
    
                $sales_requests = history_sales_requests::where('status','Completed')->orderBy('status','desc')->paginate(5);
                
                $rentalpayments = history_rental_payments::where('branchname',auth()->user()->branchname)->orderBy('status','desc')->paginate(5);
    
                $attendance = history_attendance::where('branchname',auth()->user()->branchname)->latest()->paginate(5); 
                
                $branch = branch::orderBy('branchname', 'asc')->get();

                return view('reports.index')->with(['sales' => $sales])
                    ->with(['sales_requests' => $sales_requests])
                    ->with(['attendance' => $attendance])
                    ->with(['rentalpayments' => $rentalpayments])
                    ->with(['totalsales' => $totalsales])
                    ->with(['totalqty' => $totalqty])
                    ->with(['branch' => $branch]);
                
            }
        }else{

            $sales = history_sales::where('branchname', auth()->user()->branchname)
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

            $salesget = history_sales::where('branchname', auth()->user()->branchname)
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
                    ->get();

            $totalsales = collect($salesget)->sum('total');
            $totalqty = collect($salesget)->sum('qty');

            $sales_requests = history_sales_requests::where('status','Completed')->orderBy('status','desc')->paginate(5);
        
            $rentalpayments = history_rental_payments::where('branchname',auth()->user()->branchname)->orderBy('status','desc')->paginate(5);

            $attendance = history_attendance::paginate(5); 

            return view('reports.index')->with(['sales' => $sales])
                ->with(['sales_requests' => $sales_requests])
                ->with(['attendance' => $attendance])
                ->with(['rentalpayments' => $rentalpayments])
                ->with(['totalsales' => $totalsales])
                ->with(['totalqty' => $totalqty]);
        }
        
    }

    public function rentersearch($request){
        if($request->orderrow == 'H-L'){
            $orderby = "total";
            $orderrow = 'desc';
        }elseif($request->orderrow == 'L-H'){
            $orderby = "total";
            $orderrow = 'asc';
        }elseif($request->orderrow == 'A-Z'){
            $orderby = "productname";
            $orderrow = 'asc';
        }elseif($request->orderrow == 'Z-A'){
            $orderby = "productname";
            $orderrow = 'desc';
        }elseif($request->orderrow == 'Latest'){
            $orderby = "salesid";
            $orderrow = 'desc';
        }elseif($request->orderrow == 'Oldest'){
            $orderby = "salesid";
            $orderrow = 'asc';
        }
        
        if(empty($request->search)){
            if(empty($request->startdate) && empty($request->enddate)){
                $salesget = history_sales::where('userid',auth()->user()->userid)
                                        ->orderBy($orderby,$orderrow)
                                        ->get();
                $sales = history_sales::where('userid',auth()->user()->userid)
                                        ->orderBy($orderby,$orderrow)
                                        ->paginate($request->pagerow);

                $totalqty = collect($salesget)->sum('qty');
                $totalsales = collect($salesget)->sum('total');

                $branch = branch::orderBy('branchname', 'asc')->get();
    
                $sales_requests = history_sales_requests::where('userid',auth()->user()->userid)->orderBy('status','desc')->paginate(5);
                
                $rentalpayments = history_rental_payments::where('userid',auth()->user()->userid)->orderBy('status','desc')->paginate(5);
    
                $attendance = history_attendance::where('userid',auth()->user()->userid)->latest()->paginate(5); 
                
    
                return view('reports.index')->with(['sales' => $sales])
                    ->with(['sales_requests' => $sales_requests])
                    ->with(['attendance' => $attendance])
                    ->with(['rentalpayments' => $rentalpayments])
                    ->with(['totalsales' => $totalsales])
                    ->with(['totalqty' => $totalqty])
                    ->with(['branch' => $branch]);
            }
            elseif(empty($request->startdate) or empty($request->enddate)){
                
                return redirect()->back()
                    
                    ->with('failed','Start & End Dates Required');
            }
            else{
                $startDate = Carbon::parse($request->startdate)->format('Y-m-d');
                $endDate = Carbon::parse($request->enddate)->format('Y-m-d');

                $salesget = history_sales::where('userid',auth()->user()->userid)
                                            ->whereBetween('created_at', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                                            ->orderBy($orderby,$orderrow)
                                            ->get();
                $sales = history_sales::where('userid',auth()->user()->userid)
                                        ->whereBetween('created_at', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                                        ->orderBy($orderby,$orderrow)
                                        ->paginate($request->pagerow);
                
                $totalqty = collect($salesget)->sum('qty');
                $totalsales = collect($salesget)->sum('total');
    
                $sales_requests = history_sales_requests::where('userid',auth()->user()->userid)->orderBy('status','desc')->paginate(5);
                
                $rentalpayments = history_rental_payments::where('userid',auth()->user()->userid)->orderBy('status','desc')->paginate(5);
    
                $attendance = history_attendance::where('userid',auth()->user()->userid)->latest()->paginate(5); 
                
                $branch = branch::orderBy('branchname', 'asc')->get();

                return view('reports.index')->with(['sales' => $sales])
                    ->with(['sales_requests' => $sales_requests])
                    ->with(['attendance' => $attendance])
                    ->with(['rentalpayments' => $rentalpayments])
                    ->with(['totalsales' => $totalsales])
                    ->with(['totalqty' => $totalqty])
                    ->with(['branch' => $branch]);
                
            }
        }else{

            $sales = history_sales::where('userid', auth()->user()->userid)
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

            $salesget = history_sales::where('userid', auth()->user()->userid)
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
                    ->get();

            $totalsales = collect($salesget)->sum('total');
            $totalqty = collect($salesget)->sum('qty');

            $sales_requests = history_sales_requests::where('userid',auth()->user()->userid)->orderBy('status','desc')->paginate(5);
        
            $rentalpayments = history_rental_payments::where('userid',auth()->user()->userid)->orderBy('status','desc')->paginate(5);

            $attendance = history_attendance::paginate(5); 

            return view('reports.index')->with(['sales' => $sales])
                ->with(['sales_requests' => $sales_requests])
                ->with(['attendance' => $attendance])
                ->with(['rentalpayments' => $rentalpayments])
                ->with(['totalsales' => $totalsales])
                ->with(['totalqty' => $totalqty]);
        }
    }

    public function adminsearch($request){
        if($request->orderrow == 'H-L'){
            $orderby = "total";
            $orderrow = 'desc';
        }elseif($request->orderrow == 'L-H'){
            $orderby = "total";
            $orderrow = 'asc';
        }elseif($request->orderrow == 'A-Z'){
            $orderby = "productname";
            $orderrow = 'asc';
        }elseif($request->orderrow == 'Z-A'){
            $orderby = "productname";
            $orderrow = 'desc';
        }elseif($request->orderrow == 'Latest'){
            $orderby = "salesid";
            $orderrow = 'desc';
        }elseif($request->orderrow == 'Oldest'){
            $orderby = "salesid";
            $orderrow = 'asc';
        }
        
        if(empty($request->search)){
            if(empty($request->startdate) && empty($request->enddate)){
                $salesget = history_sales::orderBy($orderby,$orderrow)
                                        ->get();
                $sales = history_sales::orderBy($orderby,$orderrow)
                                        ->paginate($request->pagerow);

                $totalqty = collect($salesget)->sum('qty'); 
                $totalsales = collect($salesget)->sum('total');

                $branch = branch::orderBy('branchname', 'asc')->get();
    
                $sales_requests = history_sales_requests::latest()->paginate(5);
                
                $rentalpayments = history_rental_payments::latest()->paginate(5);
    
                $attendance = history_attendance::latest()->paginate(5); 
                
    
                return view('reports.index')->with(['sales' => $sales])
                    ->with(['sales_requests' => $sales_requests])
                    ->with(['attendance' => $attendance])
                    ->with(['rentalpayments' => $rentalpayments])
                    ->with(['totalsales' => $totalsales])
                    ->with(['totalqty' => $totalqty])
                    ->with(['branch' => $branch]);
            }
            elseif(empty($request->startdate) or empty($request->enddate)){
                
                return redirect()->back()
                    
                    ->with('failed','Start & End Dates Required');
            }
            else{
                $startDate = Carbon::parse($request->startdate)->format('Y-m-d');
                $endDate = Carbon::parse($request->enddate)->format('Y-m-d');

                $salesget = history_sales::whereBetween('created_at', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                                            ->orderBy($orderby,$orderrow)
                                            ->get();
                $sales = history_sales::whereBetween('created_at', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                                        ->orderBy($orderby,$orderrow)
                                        ->paginate($request->pagerow);
                
                $totalqty = collect($salesget)->sum('qty');
                $totalsales = collect($salesget)->sum('total');
    
                $sales_requests = history_sales_requests::latest()->paginate(5);
                
                $rentalpayments = history_rental_payments::latest()->paginate(5);
    
                $attendance = history_attendance::latest()->paginate(5); 
                
                $branch = branch::orderBy('branchname', 'asc')->get();

                return view('reports.index')->with(['sales' => $sales])
                    ->with(['sales_requests' => $sales_requests])
                    ->with(['attendance' => $attendance])
                    ->with(['rentalpayments' => $rentalpayments])
                    ->with(['totalsales' => $totalsales])
                    ->with(['totalqty' => $totalqty])
                    ->with(['branch' => $branch]);
                
            }
        }else{

            $sales = history_sales::where('cabinetname','like',"%{$request->search}%")
                    ->where(function(Builder $builder) use($request){
                        $builder
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

            $salesget = history_sales::where('cabinetname','like',"%{$request->search}%")
                    ->where(function(Builder $builder) use($request){
                        $builder
                                ->orWhere('productname','like',"%{$request->search}%")
                                ->orWhere('qty','like',"%{$request->search}%")
                                ->orWhere('srp','like',"%{$request->search}%")
                                ->orWhere('total','like',"%{$request->search}%")
                                ->orWhere('username','like',"%{$request->search}%")
                                ->orWhere('branchname','like',"%{$request->search}%")
                                ->orWhere('snotes','like',"%{$request->search}%");
                    })
                    ->orderBy($orderby,$orderrow)
                    ->get();

            $totalsales = collect($salesget)->sum('total');
            $totalqty = collect($salesget)->sum('qty');

            $sales_requests = history_sales_requests::latest()->paginate(5);
        
            $rentalpayments = history_rental_payments::latest()->paginate(5);

            $attendance = history_attendance::latest()->paginate(5); 

            $branch = branch::orderBy('branchname', 'asc')->get();

            return view('reports.index')->with(['sales' => $sales])
                ->with(['sales_requests' => $sales_requests])
                ->with(['attendance' => $attendance])
                ->with(['branch' => $branch])
                ->with(['rentalpayments' => $rentalpayments])
                ->with(['totalsales' => $totalsales])
                ->with(['totalqty' => $totalqty]);
        }
    }

    public function displayall()
    {  
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                $salesget = history_sales::where('branchname',auth()->user()->branchname)
                                        ->latest()
                                        ->get();
                $sales = history_sales::where('branchname',auth()->user()->branchname)
                                        ->latest()
                                        ->paginate(5);

                $totalqty = collect($salesget)->sum('qty'); 
                $totalsales = collect($salesget)->sum('total');

                $branch = branch::orderBy('branchname', 'asc')->get();
    
                $sales_requests = history_sales_requests::latest()->paginate(5);
                
                $rentalpayments = history_rental_payments::latest()->paginate(5);
    
                $attendance = history_attendance::latest()->paginate(5);   

            }elseif(auth()->user()->accesstype =='Renters'){
                $salesget = history_sales::where('userid',auth()->user()->userid)
                                        ->latest()
                                        ->get();
                $sales = history_sales::where('userid',auth()->user()->userid)
                                        ->latest()
                                        ->paginate(5);

                $totalqty = collect($salesget)->sum('qty'); 
                $totalsales = collect($salesget)->sum('total');

                $branch = branch::orderBy('branchname', 'asc')->get();
    
                $sales_requests = history_sales_requests::latest()->paginate(5);
                
                $rentalpayments = history_rental_payments::latest()->paginate(5);
    
                $attendance = history_attendance::latest()->paginate(5);   
            }elseif(auth()->user()->accesstype =='Supervisor'){
                $salesget = history_sales::latest()
                                        ->get();
                $sales = history_sales::latest()
                                        ->paginate(5);

                $totalqty = collect($salesget)->sum('qty'); 
                $totalsales = collect($salesget)->sum('total');

                $branch = branch::orderBy('branchname', 'asc')->get();
    
                $sales_requests = history_sales_requests::latest()->paginate(5);
                
                $rentalpayments = history_rental_payments::latest()->paginate(5);
    
                $attendance = history_attendance::latest()->paginate(5); 

            }elseif(auth()->user()->accesstype =='Administrator'){
                $salesget = history_sales::latest()
                                        ->get();
                $sales = history_sales::latest()
                                        ->paginate(5);

                $totalqty = collect($salesget)->sum('qty'); 
                $totalsales = collect($salesget)->sum('total');

                $branch = branch::orderBy('branchname', 'asc')->get();
    
                $sales_requests = history_sales_requests::latest()->paginate(5);
                
                $rentalpayments = history_rental_payments::latest()->paginate(5);
    
                $attendance = history_attendance::latest()->paginate(5); 
            }

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
                'notes' => 'Reports',
                'status'  => 'Success',
            ]);
            return view('reports.index')->with(['sales' => $sales])
                ->with(['sales_requests' => $sales_requests])
                ->with(['attendance' => $attendance])
                ->with(['branch' => $branch])
                ->with(['rentalpayments' => $rentalpayments])
                ->with(['totalsales' => $totalsales])
                ->with(['totalqty' => $totalqty]);
        }else{
            return redirect()->route('dashboard.index');
        }
        
    }
}
