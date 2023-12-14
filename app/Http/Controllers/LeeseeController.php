<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserSearchRequest;
use App\Http\Requests\UserTableRequest;
use App\Http\Requests\UserUpdateTableRequest;
use App\Models\User;
use App\Models\SalesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Database\Eloquent\Builder;

class LeeseeController extends Controller
{
     public function displayall()
    {
        $leesee = User::where('accesstype', 'Leesee')->get();
        $sales_requests = SalesRequests::all();
        
        return view('leesee.index', ['leesee' => $leesee],['sales_requests' => $sales_requests]);
    }

    public function search(UserSearchRequest $request)
    {
        $user = User::where('accesstype',"Leesee")
                    ->where(function(Builder $builder) use($request){
                        $builder->where('username','like',"%{$request->search}%")
                                ->orWhere('firstname','like',"%{$request->search}%")
                                ->orWhere('lastname','like',"%{$request->search}%")
                                ->orWhere('middlename','like',"%{$request->search}%")
                                ->orWhere('branchname','like',"%{$request->search}%")
                                ->orWhere('email','like',"%{$request->search}%")
                                ->orWhere('status','like',"%{$request->search}%") 
                                ->orderBy('status','asc');
                    })
                    ->paginate(5);
    
        return view('renters.index',compact('user'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function index(Request $request)
    {
        $user = User::where('accesstype',"Leesee")
                    ->orderBy('status','asc')
                    ->paginate(5);
    
        return view('renters.index',compact('user'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
     
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('renters.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserTableRequest $request)
    {
        
        $user = User::create([
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
    
        if ($user) {
            //query successful
            return redirect()->route('renters.create')
                        ->with('success','User created successfully.');
        }else{
            return redirect()->route('renters.create')
                        ->with('success','User creation faild');
        }  
    }

        
     
    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('renters.show',['user' => $user]);
    } 
     
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('renters.edit',['user' => $user]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateTableRequest $request, User $user)
    {
    
        $user->update($request->all());
    
        return redirect()->route('renters.index')
                        ->with('success','User updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {

        $user->delete();
        
        $user = User::wherenot('accesstype', 'Leesee')->get();
        if ($user->isNotEmpty()) {
            
            return redirect()->route('renters.index')
            ->with('success','User deleted successfully');
        }
        else{
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect('/');
        }
        
        
    }

    
}
