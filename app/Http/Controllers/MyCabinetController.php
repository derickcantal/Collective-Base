<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\cabinet;
use App\Models\branch;
use App\Models\Renters;
use App\Models\history_sales;

use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;


class MyCabinetController extends Controller
{
    public function search()
    {
        dd("List Cabinet Search");
    }

    public function cabinetsearch()
    {
        dd("Cabinet Search");
    }
    public function loaddata(){
        $cabinets = cabinet::where('userid',auth()->user()->userid)
                    ->orderBy('status','asc')
                    ->orderBy('cabid','asc')
                    ->orderBy('branchname','asc')
                    ->paginate(5);
    
        return view('mycabinet.index',compact('cabinets'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function cabinetsales($cabinetsales)
    {
        $cabinetid = $cabinetsales;

        $history_sales = history_sales::where('cabid', $cabinetid)
                                    ->where(function(Builder $builder){
                                        $builder->where('total', '!=','0');
                                    })
                                    ->paginate(5);

        return view('mycabinet.cabsales',compact('history_sales'))

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