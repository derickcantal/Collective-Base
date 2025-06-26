<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RentalPayments;
use App\Models\user_login_log;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;

class DashboardRentalsController extends Controller
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
    public function administrator(){
        
        $rentalpayments = RentalPayments::latest()->paginate(5);

        return view('dashboard.Rentals.index')->with(['rentalpayments' => $rentalpayments])
                                        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function cashier(){

        $rentalpayments = rentalpayments::where('branchname',auth()->user()->branchname)
                    ->where(function(Builder $builder){
                    })->latest()->paginate(5);


        return view('dashboard.Rentals.index')
                    ->with(['rentalpayments' => $rentalpayments])
                    ->with('i', (request()->input('page', 1) - 1) * 5);             
    }

    public function search(Request $request)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return $this->cashier();
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->administrator();
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->administrator();
            }
        }else{
            return view('login')->with('failed','Account Inactive');
        }
        return view('dashboard.Rentals.index');
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
