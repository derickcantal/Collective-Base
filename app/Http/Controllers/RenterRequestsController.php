<?php

namespace App\Http\Controllers;

use App\Models\RenterRequests;
use Illuminate\Http\Request;
use App\Http\Requests\SalesRequestsSearchRequest;
use App\Models\Renters;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class RenterRequestsController extends Controller
{
    public function displayall()
    {
        $RenterRequests = RenterRequests::all();
        return view('dashboard.index',['RenterRequests' => $RenterRequests]);
    }

    public function search(SalesRequestsSearchRequest $request)
    {
        $RenterRequests = RenterRequests::orderBy('status','desc')
                    ->where(function(Builder $builder) use($request){
                        $builder->where('branchname','like',"%{$request->search}%")
                                ->orWhere('totalsales','like',"%{$request->search}%")
                                ->orWhere('totalcollected','like',"%{$request->search}%")
                                ->orWhere('rnotes','like',"%{$request->search}%")
                                ->orWhere('firstname','like',"%{$request->search}%")
                                ->orWhere('lastname','like',"%{$request->search}%")
                                ->orWhere('created_at','like',"%{$request->search}%")
                                ->orWhere('updated_by','like',"%{$request->search}%")
                                ->orWhere('status','like',"%{$request->search}%")
                                ;
                    })
                    ->paginate(5);
    
        return view('rentersrequests.index',compact('RenterRequests'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $RenterRequests = RenterRequests::orderBy('status','desc')
        ->paginate(5);

            return view('rentersrequests.index',compact('RenterRequests'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rentersrequests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $RenterRequests = RenterRequests::create([
            'branchid' => '1',
            'branchname' => $request->branchname,
            'cabinetid' => '1',
            'cabinetname' => $request->cabinetname,
            'totalsales' => $request->totalsales,
            'totalcollected' => $request->totalcollected,
            'avatarproof' => 'avatars/cash-default.jpg',
            'rnotes' => $request->rnotes,
            'userid' => '1',
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'created_by' => Auth()->user()->email,
            'status' => 'Pending',
        ]);
    
        if ($RenterRequests) {
            //query successful
            return redirect()->route('rentersrequests.create')
                        ->with('success','Sales Request created successfully.');
        }else{
            return redirect()->route('rentersrequests.create')
                        ->with('success','Sales Request creation failed');
        }  
    }

    /**
     * Display the specified resource.
     */
    public function show(RenterRequests $RenterRequests)
    {
        return view('rentersrequests.show',['RenterRequests' => $RenterRequests]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $RenterRequests = RenterRequests::findOrFail($id);
        return view('rentersrequests.edit',['RenterRequests' => $RenterRequests]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rentreq = RenterRequests::findOrFail($id);
        $path = Storage::disk('public')->put('rentersrequests',$request->file('avatarproof'));
        // $path = $request->file('avatar')->store('avatars','public');
        
        $oldavatar = $rentreq->avatarproof;
        
        if($oldavatar == 'avatars/cash-default.jpg'){
            
        }else{
            Storage::disk('public')->delete($oldavatar);
        }
       

        RenterRequests::where('salesrid', $id)->update([
            'branchid' => '1',
            'branchname' => $request->branchname,
            'cabinetid' => '1',
            'cabinetname' => $request->cabinetname,
            'totalsales' => $request->totalsales,
            'totalcollected' => $request->totalcollected,
            'avatarproof' => $path,
            'rnotes' => $request->rnotes,
            'userid' => '1',
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'updated_by' => Auth()->user()->email,
            'status' => 'Completed',
        ]);

        
    
        return redirect()->route('rentersrequests.index')
                        ->with('success','Sales Request updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, RenterRequests $RenterRequests): RedirectResponse
    {
        $RenterRequests->delete();
        
        $RenterRequests = RenterRequests::wherenot('accesstype', 'Leesee')->get();
        if ($RenterRequests->isNotEmpty()) {
            
            return redirect()->route('rentersrequests.index')
            ->with('success','Sales Requests deleted successfully');
        }
        else{
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect('/');
        }
    }
}
