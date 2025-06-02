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

        $cabinets = cabinet::where('branchid',$branchid)
                    ->where('email', '!=' ,'Vacant')
                    ->where(function(Builder $builder) use($request){
            $builder->where('cabinetname','=',$request->search)
                    ->latest();
        })->orderBy('cabid','asc')
                    ->paginate($request->pagerow);

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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = branch::query()
                    ->orderBy('status','asc')
                    ->orderBy('branchname','asc')
                    ->paginate(5);

        return view ('transaction.cabinetsales.index',compact('branches'))
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
