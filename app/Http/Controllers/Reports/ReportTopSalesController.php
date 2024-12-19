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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
