<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\history_sales;
use App\Models\user_login_log;
use App\Models\branch;
use \Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ReportSalesController extends Controller
{
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
