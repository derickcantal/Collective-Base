<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;
use App\Models\Renter;
use App\Models\branch;
use App\Models\cabinet;
use App\Models\Sales;
use App\Models\history_sales;
use App\Models\user_login_log;

class TransactionCabinetSalesController extends Controller
{
    public function cabinetsales($cabid){

        $today = Carbon::now();
        $tmonth = $today->month;
        $tyear = $today->year;

        $cabinet = cabinet::where('cabid',$cabid)->first();

        $renter = Renter::where('rentersid',$cabinet->userid)->first();

        // dd($cabinet->userid, $cabinet, $renter);
        

        $sales = Sales::where('userid',$renter->rentersid)
                    ->where(function(Builder $builder){
                        $builder->where('collected_status','Pending')
                                ->where('total','!=',0);
                    })->get();

        

        $totalsales = collect($sales)->sum('total');
        // dd($cabinet->userid,$renter);

        $lastweek = history_sales::where('userid',$renter->rentersid)
                                            ->where(function(Builder $builder) {            
                                                $builder->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                                                        ->where('collected_status','Pending');
                                                })->get();

        $lastweeksales = collect($lastweek)->sum('total');



        $thisweek = history_sales::where('userid',$renter->rentersid)
                                            ->where(function(Builder $builder) {            
                                                $builder->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                                        ->where('collected_status','Pending');
                                                })->get();

        $thisweeksales = collect($thisweek)->sum('total');

        $lwstartweek = Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d') ;
        $lwendweek = Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d') ;
        $curstartweek = Carbon::now()->startOfWeek()->format('Y-m-d') ;
        $curendweek = Carbon::now()->startOfWeek()->addDays(5)->format('Y-m-d') ;

        // dd($lwstartweek,$lwendweek,$curstartweek,$curendweek);


        $jan =  history_sales::where('userid',$renter->rentersid)
                                ->where(function(Builder $builder) use($tyear) {  
                                    $builder->whereYear('created_at', $tyear)
                                            ->whereMonth('created_at', 1);
                                })->get();

        $jansales = collect($jan)->sum('total');

        $feb =  history_sales::where('userid',$renter->rentersid)
                                ->where(function(Builder $builder) use($tyear) {  
                                    $builder->whereYear('created_at', $tyear)
                                            ->whereMonth('created_at', 2);
                                })->get();

        $febsales = collect($feb)->sum('total');

        $mar =  history_sales::where('userid',$renter->rentersid)
                                ->where(function(Builder $builder) use($tyear) {  
                                    $builder->whereYear('created_at', $tyear)
                                            ->whereMonth('created_at', 3);
                                })->get();

        $marsales = collect($mar)->sum('total');

        $apr =  history_sales::where('userid',$renter->rentersid)
                                ->where(function(Builder $builder) use($tyear) {  
                                    $builder->whereYear('created_at', $tyear)
                                            ->whereMonth('created_at', 4);
                                })->get();

        $aprsales = collect($apr)->sum('total');

        $may =  history_sales::where('userid',$renter->rentersid)
                                ->where(function(Builder $builder) use($tyear) {  
                                    $builder->whereYear('created_at', $tyear)
                                            ->whereMonth('created_at', 5);
                                })->get();

        $maysales = collect($may)->sum('total');

        $jun =  history_sales::where('userid',$renter->rentersid)
                                ->where(function(Builder $builder) use($tyear) {  
                                    $builder->whereYear('created_at', $tyear)
                                            ->whereMonth('created_at', 6);
                                })->get();

        $junsales = collect($jun)->sum('total');

        $jul =  history_sales::where('userid',$renter->rentersid)
                                ->where(function(Builder $builder) use($tyear) {  
                                    $builder->whereYear('created_at', $tyear)
                                            ->whereMonth('created_at', 7);
                                })->get();

        $julsales = collect($jul)->sum('total');

        $aug =  history_sales::where('userid',$renter->rentersid)
                                ->where(function(Builder $builder) use($tyear) {  
                                    $builder->whereYear('created_at', $tyear)
                                            ->whereMonth('created_at', 8);
                                })->get();

        $augsales = collect($aug)->sum('total');

        $sept =  history_sales::where('userid',$renter->rentersid)
                                ->where(function(Builder $builder) use($tyear) {  
                                    $builder->whereYear('created_at', $tyear)
                                            ->whereMonth('created_at', 9);
                                })->get();

        $septsales = collect($sept)->sum('total');

        $oct =  history_sales::where('userid',$renter->rentersid)
                                ->where(function(Builder $builder) use($tyear) {  
                                    $builder->whereYear('created_at', $tyear)
                                            ->whereMonth('created_at', 10);
                                })->get();

        $octsales = collect($oct)->sum('total');

        $nov =  history_sales::where('userid',$renter->rentersid)
                                ->where(function(Builder $builder) use($tyear) {  
                                    $builder->whereYear('created_at', $tyear)
                                            ->whereMonth('created_at', 11);
                                })->get();

        $novsales = collect($nov)->sum('total');

        $dec =  history_sales::where('userid',$renter->rentersid)
                                ->where(function(Builder $builder) use($tyear) {  
                                    $builder->whereYear('created_at', $tyear)
                                            ->whereMonth('created_at', 12);
                                })->get();

        $decsales = collect($dec)->sum('total');
        
         return view('transaction.cabinetsales.cabinet-sales')
                    ->with(['jansales' => $jansales])
                    ->with(['febsales' => $febsales])
                    ->with(['marsales' => $marsales])
                    ->with(['aprsales' => $aprsales])
                    ->with(['maysales' => $maysales])
                    ->with(['junsales' => $junsales])
                    ->with(['julsales' => $julsales])
                    ->with(['augsales' => $augsales])
                    ->with(['septsales' => $septsales])
                    ->with(['octsales' => $octsales])
                    ->with(['novsales' => $novsales])
                    ->with(['decsales' => $decsales])
                    ->with(['thisweeksales' => $thisweeksales])
                    ->with(['lastweeksales' => $lastweeksales])
                    ->with(['tyear' => $tyear])
                    ->with(['totalsales' => $totalsales])
                    ->with(['renter' => $renter])
                    ->with(['lwstartweek' => $lwstartweek])
                    ->with(['lwendweek' => $lwendweek])
                    ->with(['curstartweek' => $curstartweek])
                    ->with(['curendweek' => $curendweek]);
    }
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
                    ->paginate(10);

        return view ('transaction.cabinetsales.index',compact('branches'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
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
