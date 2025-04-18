<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\attendance;
use App\Models\User;
use App\Models\user_login_log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use \Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class TransactionAttendanceController extends Controller
{
    public function userlog($notes,$status)
    {
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
    public function loaddata()
    {
        $attendance = attendance::latest()
        ->paginate(5);

        $notes = 'Attendance';
        $status = 'Success';
        $this->userlog($notes,$status);

        return view('transaction.attendance.index',compact('attendance'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function loaddata_cashier()
    {
        $attendance = attendance::where('branchname',auth()->user()->branchname)
                                ->where(function(Builder $builder){
                                    $builder->where('status','Active')
                                        ->orderBy('status','asc');
                                    })->paginate(5);
        
        $notes = 'Attendance';
        $status = 'Success';
        $this->userlog($notes,$status);

        return view('transaction.attendance.index',compact('attendance'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function storedata($request)
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $users = User::findOrFail($request->userid);

        // $path = Storage::disk('public')->put('att',$request->file('avatarproof'));

        $validated = $request->validate([
            'avatarproof'=>'required|image|file',
        ]);
        
        $manager = ImageManager::imagick();
        $name_gen = hexdec(uniqid()).'.'.$request->file('avatarproof')->getClientOriginalExtension();
        
        $image = $manager->read($request->file('avatarproof'));
       
        $encoded = $image->toWebp()->save(storage_path('app/public/att/'.$name_gen.'.webp'));
        $path = 'att/'.$name_gen.'.webp';

        if(empty($request->attnotes)){
            $attnotes = 'Null';
        }else{
            $attnotes = $request->attnotes;
        }

        $attendance = attendance::create([
            'userid' => $users->userid,
            'username' =>  $users->username,
            'email' =>  $users->email,
            'avatarproof' => $path,
            'firstname' => $users->firstname,
            'lastname' => $users->lastname,
            'branchid' => auth()->user()->branchid,
            'branchname' => auth()->user()->branchname,
            'attnotes' =>  $attnotes,
            'created_by' => auth()->user()->email,
            'updated_by' => 'Null',
            'timerecorded' => $timenow,
            'posted' => 'N',
            'mod' => 0,
            'status' => 'Active',
        ]);
        
    
        if ($attendance) {
            //query successful
            $notes = 'Attendance. Create';
            $status = 'Success';
            $this->userlog($notes,$status);
            
            return redirect()->route('transactionattendance.index')
                        ->with('success','Attendance added successfully.');
        }else{
            $notes = 'Attendance. Create';
            $status = 'Failed';
            $this->userlog($notes,$status);
            return redirect()->route('transactionattendance.index')
                        ->with('failed','Attendance add failed');
        }  
    }
    
    public function updatedata($request, $attendance)
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $att1 = attendance::where('attid',$attendance->attid)->first();
        $mod = 0;
        $mod = $attendance->mod;

        $validated = $request->validate([
            'avatarproof'=>'required|image|file',
        ]);
        
        $oldavatar = $att1->avatarproof;

        if($mod != 0){
            return redirect()->route('transactionattendance.index')
                                ->with('failed','Attendance Update not allowed. Maximum Modification: Once.');
        }

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
                // $path = Storage::disk('public')->put('att',$request->file('avatarproof'));
                $manager = ImageManager::imagick();
                $name_gen = hexdec(uniqid()).'.'.$request->file('avatarproof')->getClientOriginalExtension();
                
                $image = $manager->read($request->file('avatarproof'));
            
                $encoded = $image->toWebp()->save(storage_path('app/public/att/'.$name_gen.'.webp'));
                $path = 'att/'.$name_gen.'.webp';
                $oldavatar = $attendance->avatarproof;
            }
            
            if($oldavatar == 'avatars/cash-default.jpg'){
                
            }else{
                if($oldavatar = $request->avatarproof){
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
            
        if ($att) {
            //query successful
            $notes = 'Attendance. Update';
            $status = 'Success';
            $this->userlog($notes,$status);
            
            return redirect()->route('transactionattendance.index')
                        ->with('success','Attendance Updated successfully.');
        }else{
            $notes = 'Attendance. Update';
            $status = 'Failed';
            $this->userlog($notes,$status);
          
            return redirect()->route('transactionattendance.index')
                        ->with('failed','Attendance Update failed');
        }
    }
    
    public function destroydata()
    {
        $notes = 'Attendance. Destroy';
        $status = 'Failed';
        $this->userlog($notes,$status);
        
        return redirect()->route('transactionattendance.index')
                        ->with('failed','Unknown Command');
    }


    public function search(Request $request)
    {
        if(auth()->user()->accesstype =='Cashier'){
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
        }elseif(auth()->user()->accesstype =='Renters'){
            return redirect()->route('dashboardoverview.index');
        }elseif(auth()->user()->accesstype =='Supervisor'){
            $attendance = attendance::where('status',"Active")
                    ->where(function(Builder $builder) use($request){
                        $builder->where('username','like',"%{$request->search}%")
                                ->orWhere('firstname','like',"%{$request->search}%")
                                ->orWhere('lastname','like',"%{$request->search}%")
                                ->orWhere('branchname','like',"%{$request->search}%")
                                ->orWhere('attnotes','like',"%{$request->search}%")
                                ->orWhere('status','like',"%{$request->search}%") 
                                ->orderBy('status','asc');
                    })
                    ->paginate(5);
        }elseif(auth()->user()->accesstype =='Administrator'){
            $attendance = attendance::where('status',"Active")
                    ->where(function(Builder $builder) use($request){
                        $builder->where('username','like',"%{$request->search}%")
                                ->orWhere('firstname','like',"%{$request->search}%")
                                ->orWhere('lastname','like',"%{$request->search}%")
                                ->orWhere('branchname','like',"%{$request->search}%")
                                ->orWhere('attnotes','like',"%{$request->search}%")
                                ->orWhere('status','like',"%{$request->search}%") 
                                ->orderBy('status','asc');
                    })
                    ->paginate(5);
        }
        

                    return view('transaction.attendance.index',compact('attendance'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function searchemp(Request $request)
    {
        $users = User::where('branchname',auth()->user()->branchname)->where('accesstype',"Cashier")
                    ->where(function(Builder $builder) use($request){
                        $builder->where('status',"Active")
                                ->where('username','like',"%{$request->search}%")
                                ->orWhere('firstname','like',"%{$request->search}%")
                                ->orWhere('lastname','like',"%{$request->search}%")
                                ->orWhere('middlename','like',"%{$request->search}%")
                                ->orWhere('branchname','like',"%{$request->search}%")
                                ->orWhere('cabinetname','like',"%{$request->search}%")
                                ->orWhere('email','like',"%{$request->search}%")
                                ->orWhere('status','like',"%{$request->search}%") 
                                ->orderBy('status','asc');
                    })
                    ->paginate($request->pagerow);

                    return view('transaction.attendance.create-select-emp',compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
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
       

        return view('transaction.attendance.create-select-emp',compact('users'))
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
            return view('transaction.attendance.create-put',['users' => $users]);
        }else{
            $notes = 'Attendance. Create. Duplicate. ' . $users->lastname;
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('transactionattendance.index')
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
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype =='Supervisor'){
                return $this->loaddata();
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return redirect()->route('dashboardoverview.index');
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
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype =='Supervisor'){
                return $this->storedata($request);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
      
    }

    /**
     * Display the specified resource.
     */
    public function show(attendance $attendance)
    {
        return redirect()->route('dashboardoverview.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(attendance $attendance)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return view('transaction.attendance.edit', ['attendance' => $attendance]);
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype =='Supervisor'){
                return view('transaction.attendance.edit', ['attendance' => $attendance]);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
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
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype =='Supervisor'){
                return $this->updatedata($request,$attendance);
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(attendance $attendance)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboardoverview.index');
            }elseif(auth()->user()->accesstype == 'Administrator' or auth()->user()->accesstype =='Supervisor'){
                return redirect()->route('dashboardoverview.index');
            }
        }else{
            return redirect()->route('dashboardoverview.index');
        }
    }
}
