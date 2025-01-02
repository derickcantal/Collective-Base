<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\history_rental_payments;
use App\Models\user_login_log;
use \Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ReportRentalsController extends Controller
{
    public function search(Request $request)
    {
        $rentalpayments = history_rental_payments::orderBy('status','desc')
                        ->where(function(Builder $builder) use($request){
                            $builder->where('branchname','like',"%{$request->search}%")
                                    ->orWhere('cabinetname','like',"%{$request->search}%")
                                    ->orWhere('rpamount','like',"%{$request->search}%")
                                    ->orWhere('rppaytype','like',"%{$request->search}%")
                                    ->orWhere('rpmonth','like',"%{$request->search}%")
                                    ->orWhere('rpyear','like',"%{$request->search}%")
                                    ->orWhere('rpnotes','like',"%{$request->search}%")
                                    ->orWhere('firstname','like',"%{$request->search}%")
                                    ->orWhere('lastname','like',"%{$request->search}%")
                                    ->orWhere('timerecorded','like',"%{$request->search}%")
                                    ->orWhere('updated_by','like',"%{$request->search}%")
                                    ->orWhere('status','like',"%{$request->search}%")
                                    ->orderBy('branchname','asc')
                                    ->orderBy('lastname','asc')
                                    ->orderBy('cabinetname','asc')
                                    ;
                        })
                        ->paginate(5);
        
            return view('reports.Rentals.index',compact('rentalpayments'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rentalpayments = history_rental_payments::latest()->paginate(5);

        return view('reports.Rentals.index')->with(['rentalpayments' => $rentalpayments]);
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
