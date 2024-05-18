<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;
use App\Models\sales_eod;
use App\Models\branch;

class EODController extends Controller
{
    public function search(Request $request)
    {
        $branch = branch::orderBy('branchid','asc')->get();
        if($request->branchname == 'All'){
            $saleseod = sales_eod::orderBy('seodid', $request->orderrow)
                                ->paginate($request->pagerow);

        }else{
            $saleseod = sales_eod::where('branchid', $request->branchname)
                                ->orderBy('seodid', $request->orderrow)
                                ->paginate($request->pagerow);

        }

        return view('eod.index')->with(['saleseod' => $saleseod])
                                ->with(['branch' => $branch])
                                ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branch = branch::orderBy('branchid','asc')->get();
        $saleseod = sales_eod::latest()->paginate(5);

        return view('eod.index')->with(['saleseod' => $saleseod])
                                ->with(['branch' => $branch])
                                ->with('i', (request()->input('page', 1) - 1) * 5);
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
