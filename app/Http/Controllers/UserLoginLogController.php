<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\branch;
use App\Models\user_login_log;
use Illuminate\Contracts\Database\Eloquent\Builder;
use \Carbon\Carbon;

class UserLoginLogController extends Controller
{
    public function home()
    {
        return view('welcome');
    }
    public function search(Request $request)
    {
        $branch = branch::all();

        if($request->branchname == 'All' && empty($request->search)){
            $userslog = user_login_log::orderBy('ullid',$request->orderrow)
                                    ->paginate($request->pagerow);
        }elseif($request->branchname == 'All' && !empty($request->search)){
            $userslog = user_login_log::orderBy('ullid',$request->orderrow)
                                    ->where(function(Builder $builder) use($request){
                                $builder->where('username','like',"%{$request->search}%")
                                        ->orWhere('firstname','like',"%{$request->search}%")
                                        ->orWhere('lastname','like',"%{$request->search}%")
                                        ->orWhere('middlename','like',"%{$request->search}%")
                                        ->orWhere('email','like',"%{$request->search}%")
                                        ->orWhere('branchname','like',"%{$request->search}%")
                                        ->orWhere('accesstype','like',"%{$request->search}%")
                                        ->orWhere('notes','like',"%{$request->search}%")
                                        ->orWhere('status','like',"%{$request->search}%"); 
                                    })
                                    ->paginate($request->pagerow);
        }elseif($request->branchname != 'All' && !empty($request->search)){
            $userslog = user_login_log::where('branchid',$request->branchname)
                                    ->where(function(Builder $builder) use($request){
                                $builder->where('username','like',"%{$request->search}%")
                                        ->orWhere('firstname','like',"%{$request->search}%")
                                        ->orWhere('lastname','like',"%{$request->search}%")
                                        ->orWhere('middlename','like',"%{$request->search}%")
                                        ->orWhere('email','like',"%{$request->search}%")
                                        ->orWhere('branchname','like',"%{$request->search}%")
                                        ->orWhere('accesstype','like',"%{$request->search}%")
                                        ->orWhere('notes','like',"%{$request->search}%")
                                        ->orWhere('status','like',"%{$request->search}%"); 
                                    })
                                    ->orderBy('ullid',$request->orderrow)
                                    ->paginate($request->pagerow);
        }else{
            $userslog = user_login_log::where('branchid',$request->branchname)
                                    ->orderBy('ullid',$request->orderrow)
                                    ->paginate($request->pagerow);
        }
        return view('userslog.index')
                        ->with(['userslog' => $userslog])
                        ->with(['branch' => $branch])
                        ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userslog = user_login_log::latest()->paginate(5);

        $branch = branch::all();

        return view('userslog.index')
                        ->with(['userslog' => $userslog])
                        ->with(['branch' => $branch])
                        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return redirect()->back();
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return redirect()->back();
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return redirect()->back();
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d h:i:s A');

    }
}
