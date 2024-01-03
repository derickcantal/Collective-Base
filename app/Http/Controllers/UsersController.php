<?php

namespace App\Http\Controllers;
        

use App\Http\Controllers\Controller;
use App\Http\Requests\UserSearchRequest;
use App\Http\Requests\UserTableRequest;
use App\Http\Requests\UserUpdateTableRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Database\Eloquent\Builder;


class UsersController extends Controller
{
    public function loaddata(){
        $user = User::whereNot('accesstype',"Renters")
                    ->orderBy('status','asc')
                    ->paginate(5);
    
        return view('users.index',compact('user'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function storedata($request){
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
            'cabid' => '1',
            'cabinetname' => 'Null',
            'accesstype' => $request->accesstype,
            'created_by' => auth()->user()->email,
            'updated_by' => 'Null',
            'mod' => 0,
            'status' => 'Active',
        ]);
    
        if ($user) {
            //query successful
            return redirect()->route('users.index')
                        ->with('success','User created successfully.');
        }else{
            return redirect()->route('users.index')
                        ->with('failed','User creation failed');
        }  
    }
    
    public function updatedata($request,$user){
        $mod = 0;
        $mod = $user->mod;

        if(empty($request->password)){
            $user =User::where('userid',$user->userid)->update([
                'username' => $request->username,
                'email' => $request->email,
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'birthdate' => $request->birthdate,
                'branchid' => '1',
                'branchname' => $request->branchname,
                'accesstype' => $request->accesstype,
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                'status' => $request->status,
            ]);
            if($user){
                return redirect()->route('users.index')
                            ->with('success','User updated successfully');
            }else{
                return redirect()->route('users.index')
                            ->with('failed','User update failed');
            }
        }else{
            $user =User::where('userid',$user->userid)->update([
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
                'updated_by' => auth()->user()->email,
                'mod' => $mod + 1,
                'status' => $request->status,
            ]);
            if($user){
                return redirect()->route('users.index')
                            ->with('success','User updated successfully');
            }else{
                return redirect()->route('users.index')
                            ->with('failed','User update failed');
            }
        }
    }
    
    public function destroydata(){
        if($user->status == 'Active')
        {
            User::where('userid', $user->userid)
            ->update([
            'status' => 'Inactive'
        ]);

        $user = User::wherenot('accesstype', 'Renters')->get();

        return redirect()->route('users.index')
            ->with('success','User Decativated successfully');
        }
        elseif($user->status == 'Inactive')
        {
            User::where('userid', $user->userid)
            ->update([
            'status' => 'Active'
        ]);

        $user = User::wherenot('accesstype', 'Renters')->get();

        return redirect()->route('users.index')
            ->with('success','User Activated successfully');
        }
    }

    public function search(Request $request)
    {
        $user = User::whereNot('accesstype',"Renters")
                ->where(function(Builder $builder) use($request){
                    $builder->where('username','like',"%{$request->search}%")
                            ->orWhere('firstname','like',"%{$request->search}%")
                            ->orWhere('lastname','like',"%{$request->search}%")
                            ->orWhere('middlename','like',"%{$request->search}%")
                            ->orWhere('branchname','like',"%{$request->search}%")
                            ->orWhere('accesstype','like',"%{$request->search}%")
                            ->orWhere('email','like',"%{$request->search}%")
                            ->orWhere('status','like',"%{$request->search}%") ;
                })
                ->orderBy('status','asc')
                ->paginate(5);
    
        return view('users.index',compact('user'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
        
        
    }

    public function index(Request $request)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return view('welcome');
            }elseif(auth()->user()->accesstype =='Renters'){
                return view('welcome');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->loaddata();
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->loaddata();
            }
        }else{
            return view('welcome');
        }
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
    public function store(UserTableRequest $request)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return view('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return view('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->storedata($request);         
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->storedata($request); 
            }
        }else{
            return view('welcome');
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
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return view('welcome');
            }elseif(auth()->user()->accesstype =='Renters'){
                return view('welcome');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->updatedata($request,$user);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->updatedata($request,$user);
            }
            
        }else{
            return view('welcome');
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return view('welcome');
            }elseif(auth()->user()->accesstype =='Renters'){
                return view('welcome');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->destroydata($request,$user);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->destroydata($request,$user);
            }
        }else{
            return view('welcome');
        }
    }

     public function displayall()
    {
        $user = User::wherenot('accesstype', 'Renters')->get();
        
        return view('users.index', ['user' => $user]);
    }

    
}
