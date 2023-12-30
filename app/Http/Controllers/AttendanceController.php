<?php

namespace App\Http\Controllers;

use App\Models\attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;

class AttendanceController extends Controller
{
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
        $users = User::where('branchname',auth()->user()->branchname)->where('status',"Active")
                    ->where(function(Builder $builder) use($request){
                        $builder
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
        $users = User::where('branchname',auth()->user()->branchname)
                    ->where(function(Builder $builder){
                        $builder->where('status',"Active")
                                ->orderBy('status','asc')
                                
                                ;
                    })
                    ->paginate(5);
    
        return view('attendance.create-select-emp',compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
            
    }
    public function putemp($user)
    {
        $users = User::findOrFail($user);
        return view('attendance.create-put',['users' => $users]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendance = attendance::get()->toQuery()
        ->orderBy('status','asc')
        ->paginate(5);

        return view('attendance.index',compact('attendance'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
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
    
        $users = User::findOrFail($request->userid);

        $attendance = attendance::create([
            'userid' => $users->userid,
            'username' =>  $users->username,
            'email' =>  $users->email,
            'firstname' => $users->firstname,
            'lastname' => $users->lastname,
            'branchid' => '1',
            'branchname' => $users->branchname,
            'attnotes' => $request->attnotes,
            'created_by' => auth()->user()->email,
            'updated_by' => 'Null',
            'posted' => 'N',
            'mod' => 0,
            'status' => 'Active',
        ]);
    
        if ($attendance) {
            //query successful
            return redirect()->route('attendance.index')
                        ->with('success','Attendance added successfully.');
        }else{
            return redirect()->route('attendance.index')
                        ->with('failed','Attendance add failed');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(attendance $attendance)
    {
        //
    }
}
