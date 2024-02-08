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
use \Carbon\Carbon;
use App\Models\branch;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ReportsController extends Controller
{
    public function searchtopsalesbranch(Request $request){

        if(empty($request->startdate) && empty($request->enddate)){
            if(empty($request->branchname) or $request->branchname == 'All'){
                $sales = history_sales::groupBy('cabid','cabinetname','branchname')
                ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
                ->orderBy('total_sum','desc')
                ->paginate(10);

                $salesget = history_sales::groupBy('cabid','cabinetname','branchname')
                ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
                ->orderBy('total_sum','desc')
                ->get();
            }elseif(!empty($request->branchname)){
                $sales = history_sales::groupBy('cabid','cabinetname','branchname')
                ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
                ->where('branchname', $request->branchname)
                ->orderBy('total_sum','desc')
                ->paginate(10);

                $salesget = history_sales::groupBy('cabid','cabinetname','branchname')
                ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
                ->where('branchname', $request->branchname)
                ->orderBy('total_sum','desc')
                ->get();
            }
            
        }elseif(empty($request->startdate) or empty($request->enddate)){
            $sales = history_sales::groupBy('cabid','cabinetname','branchname')
            ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
            ->orderBy('total_sum','desc')
            ->paginate(10);

            $salesget = history_sales::groupBy('cabid','cabinetname','branchname')
            ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
            ->orderBy('total_sum','desc')
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
                    ->orderBy('total_sum','desc')
                    ->paginate(10);
        
                    $salesget = history_sales::groupBy('cabid','cabinetname','branchname')
                    ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
                    ->whereBetween('created_at', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                    ->orderBy('total_sum','desc')
                    ->get();
                }elseif(!empty($request->branchname)){
                    $sales = history_sales::groupBy('cabid','cabinetname','branchname')
                    ->where('branchname', $request->branchname)
                    ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
                    ->whereBetween('created_at', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                    ->orderBy('total_sum','desc')
                    ->paginate(10);
        
                    $salesget = history_sales::groupBy('cabid','cabinetname','branchname')
                    ->where('branchname', $request->branchname)
                    ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
                    ->whereBetween('created_at', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                    ->orderBy('total_sum','desc')
                    ->get();
                }
                
            }
        }

        if(auth()->user()->accesstype == 'Cashier'){
            
        }elseif(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype == 'Supervisor'){
            
        }

        $branch = branch::orderBy('branchname', 'asc')->get();

        $totalqty = collect($salesget)->sum('qty_sum');
        $totalsales = collect($salesget)->sum('total_sum');

        return view('reports.top-sales-branch')->with(['sales' => $sales])
                                    ->with(['totalsales' => $totalsales])
                                    ->with(['totalqty' => $totalqty])
                                    ->with(['branch' => $branch]);
       

    }

    public function topsalesbranch(){
        
        

        if(auth()->user()->accesstype == 'Cashier'){
            
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
                                    ->with(['branch' => $branch]);
    }

    public function searchhsales(Request $request)
    {  
        if(empty($request->search)){
            if(empty($request->startdate) && empty($request->enddate)){
                if(auth()->user()->accesstype == 'Cashier'){
                    $salesget = history_sales::where('branchname',auth()->user()->branchname)->latest()->get();
                    $sales = history_sales::where('branchname',auth()->user()->branchname)->latest()->paginate(5);
                }elseif(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype == 'Supervisor'){
                    $sales = history_sales::latest()->paginate(5);
                    $salesget = history_sales::latest()->get();
                }
        
                $totalqty = collect($salesget)->sum('qty');
                $totalsales = collect($salesget)->sum('total');
    
                $sales_requests = history_sales_requests::where('status','Pending')->orderBy('status','desc')->paginate(5);
                
                $rentalpayments = history_rental_payments::where('status','Unpaid')->orderBy('status','desc')->paginate(5);
    
                $attendance = history_attendance::where('branchname',auth()->user()->branchname)->latest()->paginate(5); 
                
    
                return view('reports.index')->with(['sales' => $sales])
                    ->with(['sales_requests' => $sales_requests])
                    ->with(['attendance' => $attendance])
                    ->with(['rentalpayments' => $rentalpayments])
                    ->with(['totalsales' => $totalsales])
                    ->with(['totalqty' => $totalqty]);
            }elseif(empty($request->startdate) or empty($request->enddate)){
                if(auth()->user()->accesstype == 'Cashier'){
                    $salesget = history_sales::where('branchname',auth()->user()->branchname)->latest()->get();
                    $sales = history_sales::where('branchname',auth()->user()->branchname)->latest()->paginate(5);
                }elseif(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype == 'Supervisor'){
                    $sales = history_sales::latest()->paginate(5);
                    $salesget = history_sales::latest()->get();
                }
        
                $totalqty = collect($salesget)->sum('qty');
                $totalsales = collect($salesget)->sum('total');
    
                $sales_requests = history_sales_requests::where('status','Pending')->orderBy('status','desc')->paginate(5);
                
                $rentalpayments = history_rental_payments::where('status','Unpaid')->orderBy('status','desc')->paginate(5);
    
                $attendance = history_attendance::where('branchname',auth()->user()->branchname)->latest()->paginate(5); 
                
    
                return redirect()->route('reports.index')->with(['sales' => $sales])
                    ->with(['sales_requests' => $sales_requests])
                    ->with(['attendance' => $attendance])
                    ->with(['rentalpayments' => $rentalpayments])
                    ->with(['totalsales' => $totalsales])
                    ->with(['totalqty' => $totalqty])
                    ->with('failed','Start & End Dates Required');
            }else{
                // dd(Carbon::parse($request->startdate)->format('Y-m-d'));

                $startDate = Carbon::parse($request->startdate)->format('Y-m-d');
                $endDate = Carbon::parse($request->enddate)->format('Y-m-d');
                if(auth()->user()->accesstype == 'Cashier'){
                    $salesget = history_sales::where('branchname',auth()->user()->branchname)
                                            ->whereBetween('created_at', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                                            ->latest()->get();
                    $sales = history_sales::where('branchname',auth()->user()->branchname)
                                            ->whereBetween('created_at', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                                            ->latest()->paginate(5);
                }elseif(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype == 'Supervisor'){
                    $sales = history_sales::whereBetween('created_at', [$startDate .' 00:00:00', $endDate .' 23:59:59'])->latest()->paginate(5);
                    $salesget = history_sales::whereBetween('created_at', [$startDate .' 00:00:00', $endDate .' 23:59:59'])->latest()->get();
                }
        
                $totalqty = collect($salesget)->sum('qty');
                $totalsales = collect($salesget)->sum('total');
    
                $sales_requests = history_sales_requests::where('status','Pending')->orderBy('status','desc')->paginate(5);
                
                $rentalpayments = history_rental_payments::where('status','Unpaid')->orderBy('status','desc')->paginate(5);
    
                $attendance = history_attendance::where('branchname',auth()->user()->branchname)->latest()->paginate(5); 
                
    
                return view('reports.index')->with(['sales' => $sales])
                    ->with(['sales_requests' => $sales_requests])
                    ->with(['attendance' => $attendance])
                    ->with(['rentalpayments' => $rentalpayments])
                    ->with(['totalsales' => $totalsales])
                    ->with(['totalqty' => $totalqty]);
            }
            

           
        }else{
            if($request->search == 'Top Sold'){
   
                $sales = DB::table('history_sales')
                ->select(DB::raw('*, DISTINCT cabinetname, branchname, sum(total) as total'))
                ->groupBy('cabinetname', 'branchname')
                ->get();
                

                $sales_requests = history_sales_requests::where('status','Pending')->orderBy('status','desc')->paginate(5);
            
                $rentalpayments = history_rental_payments::where('status','Unpaid')->orderBy('status','desc')->paginate(5);

                $attendance = history_attendance::paginate(5); 

                $totalsales = collect($sales)->sum('total');
                $totalqty = collect($salesget)->sum('qty');

                return view('reports.index')->with(['sales' => $sales])
                    ->with(['sales_requests' => $sales_requests])
                    ->with(['attendance' => $attendance])
                    ->with(['rentalpayments' => $rentalpayments])
                    ->with(['totalsales' => $totalsales])
                    ->with(['totalqty' => $totalqty]);
            }else{
                if(auth()->user()->accesstype == 'Cashier'){
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
                                ->orWhere('snotes','like',"%{$request->search}%") 
                                ->orderBy('status','asc');
                    })
                    ->paginate(5);

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
                                ->orWhere('snotes','like',"%{$request->search}%") 
                                ->orderBy('status','asc');
                    })
                    ->get();

                    $totalsales = collect($salesget)->sum('total');
                    $totalqty = collect($salesget)->sum('qty');

                    $sales_requests = history_sales_requests::where('status','Pending')->orderBy('status','desc')->paginate(5);
                
                    $rentalpayments = history_rental_payments::where('status','Unpaid')->orderBy('status','desc')->paginate(5);

                    $attendance = history_attendance::paginate(5); 

                    return view('reports.index')->with(['sales' => $sales])
                        ->with(['sales_requests' => $sales_requests])
                        ->with(['attendance' => $attendance])
                        ->with(['rentalpayments' => $rentalpayments])
                        ->with(['totalsales' => $totalsales])
                        ->with(['totalqty' => $totalqty]);
                }elseif(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype == 'Supervisor'){
                    $sales = history_sales::query()
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
                                ->orderBy('status','asc');
                    })
                    ->paginate(5);

                    $salesget = history_sales::query()
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
                                ->orderBy('status','asc');
                    })
                    ->get();

                    $totalsales = collect($salesget)->sum('total');
                    $totalqty = collect($salesget)->sum('qty');

                    $sales_requests = history_sales_requests::where('status','Pending')->orderBy('status','desc')->paginate(5);
                
                    $rentalpayments = history_rental_payments::where('status','Unpaid')->orderBy('status','desc')->paginate(5);

                    $attendance = history_attendance::paginate(5); 

                    return view('reports.index')->with(['sales' => $sales])
                        ->with(['sales_requests' => $sales_requests])
                        ->with(['attendance' => $attendance])
                        ->with(['rentalpayments' => $rentalpayments])
                        ->with(['totalsales' => $totalsales])
                        ->with(['totalqty' => $totalqty]);

                }
            }
        }
    }
    public function displayall()
    {  
        if(auth()->user()->accesstype == 'Cashier'){
            $salesget = history_sales::where('branchname',auth()->user()->branchname)->latest()->get();
            $sales = history_sales::where('branchname',auth()->user()->branchname)->latest()->paginate(5);
        }elseif(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype == 'Supervisor'){
            $sales = history_sales::latest()->paginate(5);
            $salesget = history_sales::latest()->get();
        }

        $totalqty = collect($salesget)->sum('qty');
        $totalsales = collect($salesget)->sum('total');

        $sales_requests = history_sales_requests::where('status','Pending')->orderBy('status','desc')->paginate(5);
        
        $rentalpayments = history_rental_payments::where('status','Unpaid')->orderBy('status','desc')->paginate(5);

        $attendance = history_attendance::where('branchname',auth()->user()->branchname)->latest()->paginate(5); 

        return view('reports.index')->with(['sales' => $sales])
                                    ->with(['sales_requests' => $sales_requests])
                                    ->with(['attendance' => $attendance])
                                    ->with(['rentalpayments' => $rentalpayments])
                                    ->with(['totalsales' => $totalsales])
                                    ->with(['totalqty' => $totalqty]);
    }
}
