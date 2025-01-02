<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\history_sales;
use App\Models\branch;
use App\Models\user_login_log;
use \Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ReportTopSalesController extends Controller
{
    public function search(Request $request){
        
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
                    ->whereBetween('timerecorded', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                    ->orderBy($orderby,$orderrow)
                    ->paginate($request->pagerow);
        
                    $salesget = history_sales::groupBy('cabid','cabinetname','branchname')
                    ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
                    ->whereBetween('timerecorded', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                    ->orderBy($orderby,$orderrow)
                    ->get();
                }elseif(!empty($request->branchname)){
                    $sales = history_sales::groupBy('cabid','cabinetname','branchname')
                    ->where('branchname', $request->branchname)
                    ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
                    ->whereBetween('timerecorded', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                    ->orderBy($orderby,$orderrow)
                    ->paginate($request->pagerow);
        
                    $salesget = history_sales::groupBy('cabid','cabinetname','branchname')
                    ->where('branchname', $request->branchname)
                    ->select(DB::raw("SUM(`total`) AS `total_sum`,SUM(`qty`) AS `qty_sum`"), 'cabid', 'cabinetname','branchname')
                    ->whereBetween('timerecorded', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
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
        

        return view('reports.TopSales.index')->with(['sales' => $sales])
                                    ->with(['totalsales' => $totalsales])
                                    ->with(['totalqty' => $totalqty])
                                    ->with(['branch' => $branch])
                                    ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
       

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->accesstype == 'Cashier'){
            return redirect()->route('dashboardoverview.index');
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

        return view('reports.TopSales.index')->with(['sales' => $sales])
                                    ->with(['totalsales' => $totalsales])
                                    ->with(['totalqty' => $totalqty])
                                    ->with(['branch' => $branch])
                                    ->with('i', (request()->input('page', 1) - 1) * 10);
        return view('reports.TopSales.index');
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
