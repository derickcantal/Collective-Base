<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;
use App\Models\branch;
use App\Models\cabinet;
use App\Models\history_sales;
use App\Models\user_login_log;

class TransactionCabinetSalesController extends Controller
{
    public function listsalesupdate(Request $request,$salesid)
    {
        $cabinet = cabinet::where('cabid',$request->cabinetname)->first();

        $sales = history_sales::where('salesid',$salesid)->first();

        $mod = $sales->mod;

        $salesupdate =history_sales::where('salesid',$sales->salesid)->update([
                'cabid' => $cabinet->cabid,
                'cabinetname' => $cabinet->cabinetname,
                'userid' => $cabinet->userid,
                'username' => $cabinet->email,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
            ]);
        if($salesupdate)
        {
            return redirect()->back()
                        ->with('success','Sales Change Cabinet Successful.');
        }else
        {
            return redirect()->back()
                        ->with('failed','Sales Change Cabinet Failed.');
        }
        
    }
    public function listsalesmodify($salesid)
    {
        $sales = history_sales::where('salesid',$salesid)->first();

        $cabinet = cabinet::where('cabid', $sales->cabid)->first();


        $cabinetlist = cabinet::where('branchid',$sales->branchid)
                                ->where('email','!=','Vacant')
                                ->get();

        // dd($sales,$cabinet,$cabinetlist);

        return view ('transaction.cabinetsales.listsalesmodify',compact('sales'))
            ->with(['cabinet' => $cabinet])
            ->with(['cabinetlist' => $cabinetlist]);


    }

    public function listsalessearch(Request $request, $cabinetid)
    {
        if($request->orderrow == 'H-L'){
            $orderby = "total";
            $orderrow = 'desc';
        }elseif($request->orderrow == 'L-H'){
            $orderby = "total";
            $orderrow = 'asc';
        }elseif($request->orderrow == 'A-Z'){
            $orderby = "cabid";
            $orderrow = 'asc';
        }elseif($request->orderrow == 'Z-A'){
            $orderby = "cabid";
            $orderrow = 'desc';
        }
        
        $cabinet = cabinet::where('cabid',$cabinetid)->first();

        $branch = branch::where('branchid',$cabinet->branchid)->first();

        $sales = history_sales::where('cabid',$cabinetid)
                                ->where('productname','like',"%{$request->search}%")
                                ->paginate($request->pagerow);

        return view ('transaction.cabinetsales.listsales',compact('sales'))
            ->with(['cabinet' => $cabinet])
            ->with(['branch' => $branch])
            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
    }

    public function listsales($cabinetid)
    {
        $cabinet = cabinet::where('cabid',$cabinetid)->first();

        $branch = branch::where('branchid',$cabinet->branchid)->first();

        $sales = history_sales::where('cabid',$cabinetid)->paginate(5);

        return view ('transaction.cabinetsales.listsales',compact('sales'))
            ->with(['cabinet' => $cabinet])
            ->with(['branch' => $branch])
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function listcabinetsearch(Request $request,$branchid)
    {
        $branch = branch::where('branchid',$branchid)->first();
        if(!empty($request->search)){
            $cabinets = cabinet::where('branchid',$branch->branchid)
                    ->where(function(Builder $builder) use($request){
            $builder->where('email', '!=' ,'Vacant')
                    ->where('cabinetname','=',$request->search);
                  })->orderBy('cabid','asc')
                    ->paginate($request->pagerow);
        }else{
            $cabinets = cabinet::where('branchid',$branchid)
                    ->where('email', '!=' ,'Vacant')
                    ->orderBy('cabid','asc')
                    ->paginate($request->pagerow);
        }
        

        return view ('transaction.cabinetsales.listcabinet',compact('cabinets'))
            ->with(['branch' => $branch])
            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);

    }

    public function listcabinet($branchid)
    {
        $branch = branch::where('branchid',$branchid)->first();
        
        $cabinets = cabinet::where('branchid',$branchid)
                    ->where('email', '!=' ,'Vacant')
                    ->orderBy('cabid','asc')
                    ->paginate(5);

        return view ('transaction.cabinetsales.listcabinet',compact('cabinets'))
            ->with(['branch' => $branch])
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function search(Request $request)
    {
        $branches = branch::where('branchname','like',"%{$request->search}%")
                    ->orderBy('status','asc')
                    ->orderBy('branchname','asc')
                    ->paginate($request->pagerow);

        return view ('transaction.cabinetsales.index',compact('branches'))
            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
    }
    public function index()
    {
        $branches = branch::query()
                    ->orderBy('status','asc')
                    ->orderBy('branchname','asc')
                    ->paginate(5);

        return view ('transaction.cabinetsales.index',compact('branches'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
