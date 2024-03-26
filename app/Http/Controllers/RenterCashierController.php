<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Renters;
use App\Models\branch;
use App\Models\cabinet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;
use App\Http\Requests\RenterCreateRequests;
use App\Http\Requests\RenterSearchRequests;
use App\Http\Requests\RenterUpdateRequests;

class RenterCashierController extends Controller
{
    public function cabinetupdate(Request $request)
    {
        $cabid = $request->cabid;

        $cabinet = cabinet::where('cabid',$cabid)->first();
        $mod = 0;
        $mod = $cabinet->mod;
        $cabinets = cabinet::where('cabid', $cabinet->cabid)
                ->update([
                'cabinetprice' => $request->cabinetprice,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                ]);
        if ($cabinets) {
            return redirect()->route('renter.show',$cabinet->userid)
         ->with('success','Cabinet Update Successful.');
        }else{
            return redirect()->route('renter.show',$cabinet->userid)
         ->with('failed','Cabinet Update Failed.');
        }

        
    }

    public function cabinetmodify(Request $request){
        $cabinet = cabinet::where('cabid',$request->cabid)->first();

        return view('rentercashier.show-cabinet-modify',['cabinet' => $cabinet]);
    }


    public function cabinetdelete(Request $request){
        $cabinet = cabinet::where('cabid',$request->cabid)->first();
        dd('delete');

    }

    public function cabinetcreate(){

    }

    public function cabinetstore(Request $request){
        $cabuser = $request->cabuser;
        
        $renter = Renters::where('userid',$cabuser)->first();

        $cabinet = cabinet::where('cabid',$request->cabinetname)->first();

        $mod = 0;
        $mod = $cabinet->mod;
        
        if($cabinet->status == 'Active'){
            
        }else{
            return redirect()->route('renter.show',$renter->userid)
         ->with('failed','Inactive Cabinet.');
        }

        if($cabinet->email == 'Vacant'){
            
        }else{
            return redirect()->route('renter.show',$renter->userid)
         ->with('failed','Cabinet Occupied.');
        }

        $cabrenter = cabinet::where('userid', $renter->userid)->get();
        
        $totalcabown = count($cabrenter);

        Renters::where('userid',$renter->userid)
        ->update([
            'cabid' => $totalcabown + 1,
        ]);

        $cabinets = cabinet::where('cabid', $cabinet->cabid)
        ->update([
        'userid' => $renter->userid,
        'email' => $renter->email,
        'cabinetprice' => $request->cabinetprice,
        'updated_by' => auth()->user()->email,
        'mod' => $mod + 1,
        ]);

        if ($cabinets) {
            //query successful
            return redirect()->route('renter.show',$renter->userid)
                        ->with('success','Cabinet Assigned successfully.');
        }else{
            return redirect()->route('renter.show',$renter->userid)
                        ->with('failed','Cabinet Assigned failed');
        }

        return redirect()->route('renter.show',$renter->userid)
         ->with('success','Cabinet Assigned Successfully.');
    }

    public function cabinetsearch(Request $request)
    {
        dd('Search Cabinet');
    }
    public function cabinetadd(Request $request)
    {
        $cabuser = $request->cabuser;

        $renter = Renters::where('userid',$request->cabuser)->first();
        $cabinet = cabinet::where('branchname',auth()->user()->branchname)
                        ->where(function(Builder $builder){
                            $builder->where('email','=' ,'Vacant')
                                    ->where('status','=' ,'Active');
                        })
        ->get();


        return view('rentercashier.show-cabinet-create',compact('renter'))
                                            ->with(compact('cabinet'))
                                            ->with(['cabuser' => $cabuser]);
    }
    public function search(Request $request)
    {
        dd($request->pagerow);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->accesstype == 'Cashier'){
            $renter = Renters::where('branchname', auth()->user()->branchname)
                        ->where(function(Builder $builder){
                        $builder->where('accesstype',"Renters")
                                ->orderBy('status','asc');
                            })->paginate(5);

        return view('rentercashier.index',compact('renter'))
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
        return view('rentercashier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        return redirect()->route('renter.index')
         ->with('success','Renter created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Renters $renter)
    {
        $cabinets = cabinet::where('userid',$renter->userid)
        ->where(function(Builder $builder){
            $builder->where('branchname', auth()->user()->branchname)
                    ->orderBy('status','asc')
                    ->orderBy('branchname','asc');
        })
                    ->paginate(5);
        return view('rentercashier.show',['renter' => $renter])
            ->with(compact('cabinets'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Renters $renter)
    {

        return view('rentercashier.edit',['renter' => $renter]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $renter = Renters::where('userid',$id)->first();

        $mod = 0;
        $mod = $renter->mod;

        if($request->password == null){
            $renter =Renters::where('userid',$id)->update([
                'username' => $request->username,
                'email' => $request->email,
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'birthdate' => $request->birthdate,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
            ]);
        }else{
            $renter =Renters::where('userid',$id)->update([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'birthdate' => $request->birthdate,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
            ]);
        }

        if ($renter) {
            //query successful
            return redirect()->route('renter.index')
                        ->with('success','Renter updated successfully.');
        }else{
            return redirect()->route('renter.index')
                        ->with('failed','Renter update failed');
        }  
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $renter = Renters::where('userid',$id)->first();

        $mod = 0;
        $mod = $renter->mod;

        if($renter->status == 'Active')
        {
            Renters::where('userid', $renter->userid)
            ->update([
            'status' => 'Inactive'
        ]);

        return redirect()->route('renter.index')
            ->with('success','Renter Deactivated successfully'); 
        }
        elseif($renter->status == 'Inactive')
        {
            Renters::where('userid', $renter->userid)
            ->update([
            'status' => 'Active'
        ]);

        
        return redirect()->route('renter.index')
            ->with('success','User Activated successfully');
        }
    }
}
