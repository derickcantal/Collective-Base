<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UsersController extends Controller
{

    public function index()
    {
        $user = User::wherenot('accesstype', 'Leesee')->latest()->paginate(5);
    
        return view('users.index',compact('user'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
     
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'avatar' => ['string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'birthdate' => ['required', 'date', 'max:255'],
            'branchid' => ['integer', 'max:255'],
            'branchname' => ['required', 'string', 'max:255'],
            'accesstype' => ['required', 'string', 'max:255'],
            'status' => ['string', 'max:255'],
        ]);

        $user = User::create([
            'avatar' => 'avatar',
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
            return redirect()->route('users.create')
                        ->with('success','User created successfully.');
        }else{
            return redirect()->route('users.create')
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
        return view('users.show',['user' => $user]);
    } 
     
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit',['user' => $user]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'avatar' => ['string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'birthdate' => ['required', 'date', 'max:255'],
            'branchid' => ['integer', 'max:255'],
            'branchname' => ['required', 'string', 'max:255'],
            'accesstype' => ['required', 'string', 'max:255'],
            'status' => ['string', 'max:255'],
        ]);
    
        $user->update($request->all());
    
        return redirect()->route('users.index')
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
            
            return redirect()->route('users.index')
            ->with('success','User deleted successfully');
        }
        else{
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect('/');
        }
        
        
    }

     public function displayall()
    {
        $user = User::wherenot('accesstype', 'Leesee')->get();
        
        return view('users.index', ['user' => $user]);
    }

    
}
