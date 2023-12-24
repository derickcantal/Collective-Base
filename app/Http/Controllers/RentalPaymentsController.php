<?php

namespace App\Http\Controllers;

use App\Models\RentalPayments;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class RentalPaymentsController extends Controller
{
    public function search(Request $request)
    {
        $rentalPayments = RentalPayments::orderBy('status','desc')
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
                                ->orWhere('created_at','like',"%{$request->search}%")
                                ->orWhere('updated_by','like',"%{$request->search}%")
                                ->orWhere('status','like',"%{$request->search}%")
                                ;
                    })
                    ->paginate(5);
    
        return view('rentalpayments.index',compact('rentalPayments'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rentalPayments = RentalPayments::orderBy('status','desc')->paginate(5);

        return view('rentalpayments.index')->with(['rentalPayments' => $rentalPayments])
                                        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rentalpayments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rentalpayments = RentalPayments::create([
            'branchid' => '1',
            'branchname' => $request->branchname,
            'cabid' => '1',
            'cabinetname' => $request->cabinetname,
            'rppaytype' => $request->rppaytype,
            'rpamount' => $request->rpamount,
            'rpmonth' => $request->rpmonth,
            'rpyear' => $request->rpyear,
            'avatarproof' => 'avatars/cash-default.jpg',
            'rpnotes' => $request->rpnotes,
            'userid' => '1',
            'username' => $request->username,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'created_by' => Auth()->user()->email,
            'status' => 'Pending',
        ]);
    
        if ($rentalpayments) {
            //query successful
            return redirect()->route('rentalpayments.create')
                        ->with('success','Sales Request created successfully.');
        }else{
            return redirect()->route('rentalpayments.create')
                        ->with('success','Sales Request creation failed');
        }  
    }

    /**
     * Display the specified resource.
     */
    public function show(RentalPayments $rentalPayments)
    {
        return view('rentalpayments.show',['$rentalPayments' => $rentalPayments]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($rentalPayments)
    {
        $rentalPayments = RentalPayments::findOrFail($rentalPayments);
        return view('rentalpayments.edit',['rentalPayments' => $rentalPayments]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $rentalPayments)
    {
        $rentalPayment = RentalPayments::findOrFail($rentalPayments);
        
        $path = Storage::disk('public')->put('rentalPayments',$request->file('avatarproof'));
        // $path = $request->file('avatar')->store('avatars','public');
        
        $oldavatar = $rentalPayment->avatarproof;
        
        if($oldavatar == 'avatars/cash-default.jpg'){
            
        }else{
            Storage::disk('public')->delete($oldavatar);
        }
       

        RentalPayments::where('rpid', $rentalPayments)->update([
            'userid' => '1',
            'username' => $request->username,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'rpamount' => $request->rpamount,
            'rppaytype' => $request->rppaytype,
            'rpmonth' => $request->rpmonth,
            'rpyear' => $request->rpyear,
            'rpnotes' => $request->rpnotes,
            'branchid' => '1',
            'branchname' => $request->branchname,
            'cabid' => '1',
            'cabinetname' => $request->cabinetname,
            'avatarproof' => $path,
            'updated_by' => Auth()->user()->email,
            'status' => 'Paid',
        

        ]);

        
    
        return redirect()->route('rentalpayments.index')
                        ->with('success','Rental Payment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, RentalPayments $rentalPayments): RedirectResponse
    {
        $rentalPayments->delete();
        
        $rentalPayments = RentalPayments::wherenot('accesstype', 'Renters')->get();
        if ($rentalPayments->isNotEmpty()) {
            
            return redirect()->route('rentalpayments.index')
            ->with('success','Sales Requests deleted successfully');
        }
        else{
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect('/');
        }
    }
}
