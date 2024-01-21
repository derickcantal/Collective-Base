<?php

namespace App\Http\Controllers;

use App\Models\attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use \Carbon\Carbon;


class AttendanceController extends Controller
{
    public function loaddata(){
        $attendance = attendance::where('branchname',auth()->user()->branchname)
        ->paginate(5);

        return view('attendance.index',compact('attendance'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function loaddata_cashier(){
        $attendance = attendance::where('branchname',auth()->user()->branchname)
                                ->where(function(Builder $builder){
                                    $builder->where('status','Active')
                                        ->orderBy('status','asc');
                                    })->paginate(5);

        return view('attendance.index',compact('attendance'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function storedata($request){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');
        $users = User::findOrFail($request->userid);

        $path = Storage::disk('public')->put('att',$request->file('avatarproof'));

        if(empty($request->attnotes)){
            $attendance = attendance::create([
                'userid' => $users->userid,
                'username' =>  $users->username,
                'email' =>  $users->email,
                'avatarproof' => $path,
                'firstname' => $users->firstname,
                'lastname' => $users->lastname,
                'branchid' => auth()->user()->branchid,
                'branchname' => auth()->user()->branchname,
                'attnotes' => 'Null',
                'created_by' => auth()->user()->email,
                'updated_by' => 'Null',
                'timerecorded' => $timenow,
                'posted' => 'N',
                'mod' => 0,
                'status' => 'Active',
            ]);
        }else{
            $attendance = attendance::create([
                'userid' => $users->userid,
                'username' =>  $users->username,
                'email' =>  $users->email,
                'avatarproof' => $path,
                'firstname' => $users->firstname,
                'lastname' => $users->lastname,
                'branchid' => auth()->user()->branchid,
                'branchname' => auth()->user()->branchname,
                'attnotes' => $request->attnotes,
                'created_by' => auth()->user()->email,
                'updated_by' => 'Null',
                'timerecorded' => $timenow,
                'posted' => 'N',
                'mod' => 0,
                'status' => 'Active',
            ]);
        }
        
    
        if ($attendance) {
            //query successful
            return redirect()->route('attendance.index')
                        ->with('success','Attendance added successfully.');
        }else{
            return redirect()->route('attendance.index')
                        ->with('failed','Attendance add failed');
        }  
    }
    
    public function updatedata($request, $attendance){
        
        $att1 = attendance::where('attid',$attendance->attid)->first();
        $mod = 0;
        $mod = $attendance->mod;
        if($mod == 0){
            if(empty($request->avatarproof)){
                if(empty($request->attnotes)){
                    $att =attendance::where('attid',$attendance->attid)->update([
                        'updated_by' => auth()->user()->email,
                        'mod' => $mod + 1,
                    ]);
                }else{
                    $att =attendance::where('attid',$attendance->attid)->update([
                        'attnotes' => $request->attnotes,
                        'updated_by' => auth()->user()->email,
                        'mod' => $mod + 1,
                    ]);
                }
    
                
            }else{
                
                if(!empty($request->avatarproof)){
                    $path = Storage::disk('public')->put('att',$request->file('avatarproof'));
                    $oldavatar = $attendance->avatarproof;
                }
                
                if($oldavatar == 'avatars/cash-default.jpg'){
                    
                }else{
                    Storage::disk('public')->delete($oldavatar);
                    if(!empty($request->avatarproof)){
                        Storage::disk('public')->delete($oldavatar);
                    }
    
                }
                if(empty($request->attnotes)){
                    $att =attendance::where('attid',$attendance->attid)->update([
                        'avatarproof' => $path,
                        'updated_by' => auth()->user()->email,
                        'mod' => $mod + 1,
                    ]);
                }else{
                    $att =attendance::where('attid',$attendance->attid)->update([
                        'avatarproof' => $path,
                        'attnotes' => $request->attnotes,
                        'updated_by' => auth()->user()->email,
                        'mod' => $mod + 1,
                    ]);
                }
            }
        }else{
            return redirect()->route('attendance.index')
                        ->with('failed','Attendance Update not allowed. Maximum Modification: Once.');
        }
        
        if ($att) {
            //query successful
            return redirect()->route('attendance.index')
                        ->with('success','Attendance Updated successfully.');
        }else{
            return redirect()->route('attendance.index')
                        ->with('failed','Attendance Update failed');
        }
    }
    
    public function destroydata(){
    
    }


    public function search(Request $request)
    {
        $attendance = attendance::where('branchname',auth()->user()->branchname)->where('status',"Active")
                    ->where(function(Builder $builder) use($request){
                        $builder
                                ->where('username','like',"%{$request->search}%")
                                ->orWhere('firstname','like',"%{$request->search}%")
                                ->orWhere('lastname','like',"%{$request->search}%")
                                ->orWhere('branchname','like',"%{$request->search}%")
                                ->orWhere('attnotes','like',"%{$request->search}%")
                                ->orWhere('status','like',"%{$request->search}%") 
                                ->orderBy('status','asc');
                    })
                    ->paginate(5);

                    return view('attendance.index',compact('attendance'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function searchemp(Request $request)
    {
        $users = User::where('branchname',auth()->user()->branchname)->where('accesstype',"Cashier")
                    ->where(function(Builder $builder) use($request){
                        $builder->where('status',"Active")
                                ->where('username','like',"%{$request->searchemp}%")
                                ->orWhere('firstname','like',"%{$request->searchemp}%")
                                ->orWhere('lastname','like',"%{$request->searchemp}%")
                                ->orWhere('middlename','like',"%{$request->searchemp}%")
                                ->orWhere('branchname','like',"%{$request->searchemp}%")
                                ->orWhere('cabinetname','like',"%{$request->searchemp}%")
                                ->orWhere('email','like',"%{$request->searchemp}%")
                                ->orWhere('status','like',"%{$request->searchemp}%") 
                                ->orderBy('status','asc');
                    })
                    ->paginate(5);

                    return view('attendance.create-select-emp',compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function selectemp()
    {
        $attendance = DB::table('attendance')
                ->select(DB::raw(1))
                ->whereColumn('attendance.userid', 'users.userid');
 
        $lists = DB::table('users')
                            ->where('status', "Active")
                            ->where('accesstype',auth()->user()->accesstype)
                            ->where('branchname',auth()->user()->branchname)
                            ->whereExists($attendance)
                            ->orderBy('status','asc')
                            ->get();
                            
     

            $users = User::where('accesstype',auth()->user()->accesstype)
            ->where(function(Builder $builder){
                $builder->where('status',"Active")
                        ->where('branchname',auth()->user()->branchname)
                        ->orderBy('status','asc');
            })
            ->paginate(5);
       

        return view('attendance.create-select-emp',compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
            
    }
    public function putemp($user)
    {
        $attendance = attendance::where('userid', $user)
                                ->where(function(Builder $builder){
                        $builder->where('status',"Active")
                                ->where('branchname',auth()->user()->branchname);
                            })->first();
        $users = User::findOrFail($user);                    
        if(empty($attendance->userid)){
            return view('attendance.create-put',['users' => $users]);
        }else{
            return redirect()->route('attendance.index')
                        ->with('failed','Employee Already Added to Attendance');
        }
        

        
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return $this->loaddata_cashier();
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->loaddata();
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->loaddata();
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return $this->storedata($request);
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->storedata($request);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->storedata($request);
            }
        }else{
            return redirect()->route('dashboard.index');
        }
      
    }

    /**
     * Display the specified resource.
     */
    public function show(attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(attendance $attendance)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return view('attendance.edit', ['attendance' => $attendance]);
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return view('attendance.edit', ['attendance' => $attendance]);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return view('attendance.edit', ['attendance' => $attendance]);
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, attendance $attendance)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return $this->updatedata($request,$attendance);
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->updatedata($request,$attendance);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->updatedata($request,$attendance);
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(attendance $attendance)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                
            }elseif(auth()->user()->accesstype =='Renters'){
                
            }elseif(auth()->user()->accesstype =='Supervisor'){
                
            }elseif(auth()->user()->accesstype =='Administrator'){
                
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }
}
