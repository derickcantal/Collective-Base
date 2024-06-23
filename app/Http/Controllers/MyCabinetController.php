<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\cabinet;
use App\Models\branch;
use App\Models\Renters;
use App\Models\history_sales;
use App\Models\user_login_log;

use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;


class MyCabinetController extends Controller
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

        $cabinets = cabinet::where('userid',auth()->user()->userid)
                    ->orderBy('status','asc')
                    ->orderBy('cabid','asc')
                    ->orderBy('branchname','asc')
                    ->paginate($request->pagerow);
    
        return view('mycabinet.index',compact('cabinets'))
            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
    }

    public function cabinetsearch(Request $request, $cabinetid)
    {
        $cabinet = cabinet::where('cabid', $cabinetid)
                            ->latest()
                            ->first();

        if($cabinet->userid != auth()->user()->userid)
        {
            return redirect()->route('dashboard.index')
                                ->with('failed','Unknown Command.');
        }
        
        if(empty($request->search)){
            if(!empty($request->startdate) && empty($request->enddate))
            {
                return redirect()->back()
                ->with('failed','Start and End Date are both Required input');  
            }
            elseif(empty($request->startdate) && !empty($request->enddate))
            {
                return redirect()->back()
                ->with('failed','Start and End Date are both Required input');  
            }
            elseif(!empty($request->startdate) && !empty($request->enddate)){
                $startDate = Carbon::parse($request->startdate)->format('Y-m-d');
                $endDate = Carbon::parse($request->enddate)->format('Y-m-d');
    
    
                $history_sales = history_sales::where('cabid', $cabinetid)
                            ->where(function(Builder $builder) use($startDate,$endDate){
                                $builder->where('total', '!=','0')
                                        ->whereBetween('timerecorded', [$startDate .' 00:00:00', $endDate .' 23:59:59']);
                            })
                            ->paginate($request->pagerow);
    
                            
            }else{
                $history_sales = history_sales::where('cabid', $cabinetid)
                        ->where(function(Builder $builder){
                            $builder->where('total', '!=','0');
                        })
                        ->paginate($request->pagerow);
            }
            
        }elseif(!empty($request->search)){
            if(!empty($request->startdate) && empty($request->enddate)){
                return redirect()->back()
                ->with('failed','Start and End Date are both Required input');  
            }
            elseif(empty($request->startdate) && !empty($request->enddate))
            {
                return redirect()->back()
                ->with('failed','Start and End Date are both Required input');  
            }
            elseif(!empty($request->startdate) && !empty($request->enddate)){
                $startDate = Carbon::parse($request->startdate)->format('Y-m-d');
                $endDate = Carbon::parse($request->enddate)->format('Y-m-d');
    
                $history_sales = history_sales::where('cabid', $cabinetid)
                            ->where(function(Builder $builder) use($request,$startDate,$endDate){
                                $builder->where('total', '!=','0')
                                        ->whereBetween('timerecorded', [$startDate .' 00:00:00', $endDate .' 23:59:59'])
                                        ->where('productname','like',"%{$request->search}%")
                                        ->orWhere('srp','like',"%{$request->search}%")
                                        ->orWhere('total','like',"%{$request->search}%");
                            })
                            ->paginate($request->pagerow);
    
                            
            }else{
                $history_sales = history_sales::where('cabid', $cabinetid)
                        ->where(function(Builder $builder) use($request){
                            $builder->where('total', '!=','0')
                                    ->where('productname','like',"%{$request->search}%")
                                    ->orWhere('srp','like',"%{$request->search}%")
                                    ->orWhere('total','like',"%{$request->search}%");
                        })
                        ->paginate($request->pagerow);
            }
            
        }
        
        return view('mycabinet.cabsales',compact('history_sales'))
                                    ->with(['cabinetid' => $cabinetid])
                                    ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);  
      
    }
    public function loaddata(){
        $cabinets = cabinet::where('userid',auth()->user()->userid)
                    ->orderBy('status','asc')
                    ->orderBy('cabid','asc')
                    ->orderBy('branchname','asc')
                    ->paginate(5);

        $notes = 'My Cabinet';
        $status = 'Success';
        $this->userlog($notes,$status);

        return view('mycabinet.index',compact('cabinets'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function cabinetsales($cabinetsales)
    {
        $today = Carbon::now();
        $today->month;
        $today->year;


        $cabinet = cabinet::where('cabid', $cabinetsales)
                            ->latest()
                            ->first();

        // dd($today->month == $cabinet->rpmonth,$today->year == $cabinet->rpyear);


        if($cabinet->userid != auth()->user()->userid)
        {
            $notes = 'My Cabinet. Accessing not own account cabinet';
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('mycabinet.index')
                                ->with('failed','Unknown Command.');
        }

        if($today->month == $cabinet->rpmonth && $today->year == $cabinet->rpyear)
        {
            if($cabinet->fully_paid == 'N' or empty($cabinet->fully_paid))
            {
                $notes = 'My Cabinet. Unsettled Account';
                $status = 'Failed';
                $this->userlog($notes,$status);
                return redirect()->back()
                                ->with('failed','please settle this account rental payment first.');
            }
        }
        // else
        // {
        //     dd('not same');
        // }                   

        // dd($today->month, $today->year);

        $cabinetid = $cabinetsales;

        $history_sales = history_sales::where('cabid', $cabinetid)
                                    ->where(function(Builder $builder){
                                        $builder->where('total', '!=','0');
                                    })
                                    ->paginate(5);

        return view('mycabinet.cabsales',compact('history_sales'))
                            ->with(['cabinetid' => $cabinetid])
                            ->with('i', (request()->input('page', 1) - 1) * 5);                

    }

    public function index()
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return $this->loaddata();
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->loaddata();
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }
}
