<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\history_sales;
use App\Models\Renter;
use App\Models\user_login_log;
use App\Models\branch;
use \Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ReportSalesController extends Controller
{
    public function search(Request $request)
    {  
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return $this->cashiersearch($request);  
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->adminsearch($request);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->adminsearch($request);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
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
    
    
                return view('reports.Sales.index')->with(['sales' => $sales])
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
                                            ->whereBetween('timerecorded', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                                            ->orderBy($orderby,$orderrow)
                                            ->get();
                $sales = history_sales::where('branchname',auth()->user()->branchname)
                                        ->whereBetween('timerecorded', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                                        ->orderBy($orderby,$orderrow)
                                        ->paginate($request->pagerow);
                
                $totalqty = collect($salesget)->sum('qty');
                $totalsales = collect($salesget)->sum('total');
    
                $branch = branch::orderBy('branchname', 'asc')->get();

                return view('reports.Sales.index')->with(['sales' => $sales])
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

            return view('reports.Sales.index')->with(['sales' => $sales])
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
    
                return view('reports.Sales.index')->with(['sales' => $sales])
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

                $salesget = history_sales::whereBetween('timerecorded', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                                            ->orderBy($orderby,$orderrow)
                                            ->get();
                $sales = history_sales::whereBetween('timerecorded', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                                        ->orderBy($orderby,$orderrow)
                                        ->paginate($request->pagerow);
                
                $totalqty = collect($salesget)->sum('qty');
                $totalsales = collect($salesget)->sum('total');
    
                $branch = branch::orderBy('branchname', 'asc')->get();

                return view('reports.Sales.index')->with(['sales' => $sales])
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

            $branch = branch::orderBy('branchname', 'asc')->get();

            return view('reports.Sales.index')->with(['sales' => $sales])
                ->with(['branch' => $branch])
                ->with(['totalsales' => $totalsales])
                ->with(['totalqty' => $totalqty]);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
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
    
            }elseif(auth()->user()->accesstype =='Supervisor'){
                $salesget = history_sales::latest()
                                        ->get();
                $sales = history_sales::latest()
                                        ->paginate(5);

                $totalqty = collect($salesget)->sum('qty'); 
                $totalsales = collect($salesget)->sum('total');

                $branch = branch::orderBy('branchname', 'asc')->get();
    

            }elseif(auth()->user()->accesstype =='Administrator'){
                $salesget = history_sales::latest()
                                        ->get();
                $sales = history_sales::latest()
                                        ->paginate(5);

                $totalqty = collect($salesget)->sum('qty'); 
                $totalsales = collect($salesget)->sum('total');

                $branch = branch::orderBy('branchname', 'asc')->get();
    
            }

  
            return view('reports.Sales.index')->with(['sales' => $sales])
                ->with(['branch' => $branch])
                ->with(['totalsales' => $totalsales])
                ->with(['totalqty' => $totalqty]);
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show($salesid)
    {
        $sales = history_sales::where('salesid',$salesid)->first();

        $renter = Renter::where('rentersid',$sales->userid)->first();
        // dd($salesid,$sales, $renter);

        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return view('reports.Sales.show',['sales' => $sales]);
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return view('reports.Sales.show',['sales' => $sales]);     
            }elseif(auth()->user()->accesstype =='Administrator'){
                return view('reports.Sales.show',['sales' => $sales]);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }

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
