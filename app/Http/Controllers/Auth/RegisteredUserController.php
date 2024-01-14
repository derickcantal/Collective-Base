<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserTableRequest;
use App\Models\User;
use App\Models\branch;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use \Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $branch = branch::orderBy('branchname', 'asc')->get();
        return view('auth.register',['branch' => $branch]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserTableRequest $request): RedirectResponse
    {
        $branch = branch::where('branchname', $request->branchname)->first();

        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s a');
        $user = User::create([
            'avatar' => 'avatars/avatar-default.jpg',
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'birthdate' => $request->birthdate,
            'branchid' => $branch->branchid,
            'branchname' => $branch->branchname,
            'cabid' => 0,
            'cabinetname' => 'Null',
            'accesstype' => $request->accesstype,
            'created_by' =>$request->email,
            'updated_by' => 'Null', 
            'timerecorded' => $timenow,
            'mod' => 0,
            'status' => 'Active',
        ]);

        
        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
