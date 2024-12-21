<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;
use App\Models\sales_eod;
use App\Models\user_login_log;
use App\Models\branch;

class TransactionEODController extends Controller
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
    
    public function search(Request $request)
    {
        if(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype =='Supervisor'){
            $branch = branch::orderBy('branchid','asc')->get();
            if($request->branchname == 'All'){
                $saleseod = sales_eod::orderBy('seodid', $request->orderrow)
                                    ->paginate($request->pagerow);

            }else{
                $saleseod = sales_eod::where('branchid', $request->branchname)
                                    ->orderBy('seodid', $request->orderrow)
                                    ->paginate($request->pagerow);

            }

            return view('transaction.eod.index')->with(['saleseod' => $saleseod])
                                    ->with(['branch' => $branch])
                                    ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
        }else{
            return redirect()->route('dashboard.index');
        }  
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype =='Supervisor'){
            $branch = branch::orderBy('branchid','asc')->get();
            $saleseod = sales_eod::latest()->paginate(5);

            $notes = 'EOD';
            $status = 'Success';
            $this->userlog($notes,$status);
            
            return view('transaction.eod.index')->with(['saleseod' => $saleseod])
                                    ->with(['branch' => $branch])
                                    ->with('i', (request()->input('page', 1) - 1) * 5);
        }else{
            return redirect()->route('dashboard.index');
        }  
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype =='Supervisor'){
            return redirect()->route('dashboard.index');
        }else{
            return redirect()->route('dashboard.index');
        }  
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype =='Supervisor'){
            return redirect()->route('dashboard.index');
        }else{
            return redirect()->route('dashboard.index');
        }  
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype =='Supervisor'){
            return redirect()->route('dashboard.index');
        }else{
            return redirect()->route('dashboard.index');
        }  
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype =='Supervisor'){
            return redirect()->route('dashboard.index');
        }else{
            return redirect()->route('dashboard.index');
        }  
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype =='Supervisor'){
            return redirect()->route('dashboard.index');
        }else{
            return redirect()->route('dashboard.index');
        }  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype =='Supervisor'){
            return redirect()->route('dashboard.index');
        }else{
            return redirect()->route('dashboard.index');
        }  
    }
}
