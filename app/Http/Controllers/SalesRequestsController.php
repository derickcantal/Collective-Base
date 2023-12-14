<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalesRequestsSearchRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Models\SalesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Database\Eloquent\Builder;


class SalesRequestsController extends Controller
{
    public function displayall()
    {
        $SalesRequests = SalesRequests::all();
        return view('dashboard.index',['SalesRequests' => $SalesRequests]);
    }

    public function search(SalesRequestsSearchRequest $request)
    {
        $SalesRequests = SalesRequests::orderBy('status','desc')
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
    
        return view('rentersrequests.index',compact('SalesRequests'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function index(Request $request)
    {
        $SalesRequests = SalesRequests::orderBy('status','desc')
                        ->paginate(5);
    
        return view('rentersrequests.index',compact('SalesRequests'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
     
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('rentersrequests.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $SalesRequests = SalesRequests::create([
            'avatar' => 'avatars/avatar-default.jpg',
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'birthdate' => $request->birthdate,
            'branchid' => '1',
            'branchname' => $request->branchname,
            'accesstype' => $request->accesstype,
            'status' => 'Active',
        ]);
    
        if ($SalesRequests) {
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
     *
     * @param  \App\User  $SalesRequests
     * @return \Illuminate\Http\Response
     */
    public function show(SalesRequests $SalesRequests)
    {
        return view('rentersrequests.show',['SalesRequests' => $SalesRequests]);
    } 
     
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $SalesRequests)
    {
        return view('rentersrequests.edit',['SalesRequests' => $SalesRequests]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Request $SalesRequests)
    {
    
        $SalesRequests->update($request->all());
    
        return redirect()->route('rentersrequests.index')
                        ->with('success','Sales Request updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, SalesRequests $SalesRequests): RedirectResponse
    {

        $SalesRequests->delete();
        
        $SalesRequests = SalesRequests::wherenot('accesstype', 'Leesee')->get();
        if ($SalesRequests->isNotEmpty()) {
            
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
