<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserSearchRequest;
use App\Http\Requests\UserTableRequest;
use App\Http\Requests\UserUpdateTableRequest;
use App\Models\User;
use App\Models\branch;
use App\Models\cabinet;
use App\Models\user_login_log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use \Carbon\Carbon;
use League\CommonMark\Extension\Embed\Bridge\OscaroteroEmbedAdapter;

class ManageUserController extends Controller
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
    public function loaddata(){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

        $user = User::whereNot('accesstype',"Renters")
                    ->orderBy('status','asc')
                    ->paginate(5);

        $notes = 'Users';
        $status = 'Success';
        $this->userlog($notes,$status);

        return view('manage.users.index',compact('user'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function storedata($request){
        $n1 = strtoupper($request->firstname[0]);
        // $n2 = strtoupper($request->middlename[0]);
        $n3 = strtoupper($request->lastname[0]);
        $n4 = preg_replace('/[-]+/', '', $request->birthdate);

        // $newpassword = $n1 . $n2 . $n3 . $n4;
        $newpassword = $n1 . $n3 . $n4;
        //dd($newpassword);

        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $br = branch::where('branchname',$request->branchname)->first();

        $cabn = cabinet::where('cabinetname',$request->cabinetname)
        ->where(function(Builder $builder) use($request){
            $builder->where('branchname',$request->branchname);
        })->first();

        if(auth()->user()->accesstype == 'Supervisor')
        {
            if($request->accesstype == 'Adminstrator')
            {
                $notes = 'Users. Create. Admin Account';
                $status = 'Failed';
                $this->userlog($notes,$status);

                return redirect()->route('manageuser.index')
                        ->with('failed','User creation failed');
            }
            elseif($request->accesstype == 'Supervisor')
            {
                $notes = 'Users. Create. Supervisor Account';
                $status = 'Failed';
                $this->userlog($notes,$status);

                return redirect()->route('manageuser.index')
                        ->with('failed','User creation failed');
            }
        }
        $user = User::create([
            'avatar' => 'avatars/avatar-default.jpg',
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($newpassword),
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'birthdate' => $request->birthdate,
            'branchid' => $br->branchid,
            'branchname' => $br->branchname,
            'cabid' => 0,
            'cabinetname' => 'Null',
            'accesstype' => $request->accesstype,
            'created_by' => auth()->user()->email,
            'updated_by' => 'Null',
            'timerecorded' => $timenow,
            'mod' => 0,
            'status' => 'Active',
        ]);
    
        if ($user) {
            //query successful
            $notes = 'Users. Create.';
            $status = 'Success';
            $this->userlog($notes,$status);
            
            return redirect()->route('manageuser.index')
                        ->with('success','User created successfully.');
        }else{
            $notes = 'Users. Create.';
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('manageuser.index')
                        ->with('failed','User creation failed');
        }  
    }
    
    public function updatedata($request,$user){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

        $mod = 0;
        $mod = $user->mod;

        if(auth()->user()->accesstype == 'Supervisor')
        {
            if($request->accesstype == 'Administrator')
            {
                $notes = 'Users. Create. Admin Account';
                $status = 'Failed';
                $this->userlog($notes,$status);
                
                return redirect()->route('manageuser.index')
                        ->with('failed','User update failed');
            }
            elseif($request->accesstype == 'Supervisor')
            {
                $notes = 'Users. Create. Supervisor Account';
                $status = 'Failed';
                $this->userlog($notes,$status);
                
                return redirect()->route('manageuser.index')
                        ->with('failed','User update failed');
            }
        }
        if(!empty($request->password) != !empty($request->password_confirmation)){
            $notes = 'Users. Create. Password Mismatched. ' . $request->lastname;
            $status = 'Failed';
            $this->userlog($notes,$status);
            
            return redirect()->route('manageuser.index')
                    ->with('failed','User update failed');
        }
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
                $notes = 'Users. Update.';
                $status = 'Success';
                $this->userlog($notes,$status);
               
                return redirect()->route('manageuser.index')
                            ->with('success','User updated successfully');
            }else{
                $notes = 'Users. Update.';
                $status = 'Failed';
                $this->userlog($notes,$status);

                return redirect()->route('manageuser.index')
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
                $notes = 'Users. Update.';
                $status = 'Success';
                $this->userlog($notes,$status);

                
                return redirect()->route('manageuser.index')
                            ->with('success','User updated successfully');
            }else{
                $notes = 'Users. Update.';
                $status = 'Failed';
                $this->userlog($notes,$status);
                
                return redirect()->route('manageuser.index')
                            ->with('failed','User update failed');
            }
        }
    }
    
    public function destroydata($request,$user){
        $fullname = $user->lastname . ', ' . $user->firstname . ' ' . $user->middlename;

        if($user->userid == auth()->user()->userid){
            $notes = 'Users. Activation. Self Account. ' . $fullname;
                $status = 'Failed';
                $this->userlog($notes,$status);

                return redirect()->route('manageuser.index')
                        ->with('failed','User Update on own account not allowed.');
        }
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        if(auth()->user()->accesstype == 'Supervisor')
        {
            if($user->accesstype == 'Administrator')
            {
                $notes = 'Users. Activation. Admin Account. ' . $fullname;
                $status = 'Failed';
                $this->userlog($notes,$status);

                return redirect()->route('manageuser.index')
                        ->with('failed','User update failed');
            }
            elseif($user->accesstype == 'Supervisor')
            {
                $notes = 'Users. Activation. Supervisor Account. ' . $fullname;
                $status = 'Failed';
                $this->userlog($notes,$status);

                return redirect()->route('manageuser.index')
                        ->with('failed','User update failed');
            }
        }

        if($user->status == 'Active')
        {
            User::where('userid', $user->userid)
            ->update([
            'BLID' => 0,
            'status' => 'Inactive',
        ]);

        $user = User::wherenot('accesstype', 'Renters')->get();

        $notes = 'Users. Deactivate. ' . $fullname;
        $status = 'Success';
        $this->userlog($notes,$status);

        return redirect()->route('manageuser.index')
            ->with('success','User Decativated successfully');
        }
        elseif($user->status == 'Inactive')
        {
            User::where('userid', $user->userid)
            ->update([
            'BLID' => 1,
            'status' => 'Active',
        ]);

        $user = User::wherenot('accesstype', 'Renters')->get();

        $notes = 'Users. Activate. ' . $fullname;
        $status = 'Success';
        $this->userlog($notes,$status);

        return redirect()->route('manageuser.index')
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
                            ->orWhere('email','like',"%{$request->search}%")
                            ->orWhere('branchname','like',"%{$request->search}%")
                            ->orWhere('accesstype','like',"%{$request->search}%")
                            ->orWhere('status','like',"%{$request->search}%"); 
                            
                })
                ->orderBy('lastname',$request->orderrow)
                ->paginate($request->pagerow);
    
        return view('manage.users.index',compact('user'))
            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
        
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->loaddata();
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->loaddata();
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
        return view('manage.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branch = branch::orderBy('branchname', 'asc')->get();
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return view('manage.users.create',['branch' => $branch]);       
            }elseif(auth()->user()->accesstype =='Administrator'){
                return view('manage.users.create',['branch' => $branch]);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserTableRequest $request)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->storedata($request);         
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->storedata($request); 
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $branch = branch::all();

        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return view('manage.users.show',['user' => $user, 'branch' => $branch]);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return view('manage.users.show',['user' => $user, 'branch' => $branch]);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if(auth()->user()->accesstype == 'Supervisor')
        {
            if($user->accesstype == 'Administrator'){
                return redirect()->route('users.index')
                ->with('failed','User modification not allowed');
            }elseif($user->accesstype == 'Supervisor'){
                return redirect()->route('users.index')
                ->with('failed','User modification not allowed');
            }
            
        }

        return view('manage.users.edit',['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->updatedata($request,$user);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->updatedata($request,$user);
            }
            
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->destroydata($request,$user);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->destroydata($request,$user);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }
}
