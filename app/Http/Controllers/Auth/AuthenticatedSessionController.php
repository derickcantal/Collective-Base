<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use \Carbon\Carbon;
use App\Models\User;
use App\Models\user_login_log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        if (now()->isBetween('09:00:00', '21:00:00')) {
            $user = User::where('accesstype','Cashier')->where('accesstype','Active')->first();
            if($user){
                User::where('accesstype','Cashier')->where('status','Inactive')->update([
                    'status' => 'Active',
                    'BLID' => 0,
                ]);
            }
            
            #dd('Access Allowed',now());

        }
        else
        {
            $user = User::where('accesstype','Cashier')->where('status','Active')->first();
            if($user){
                if(now()->isBetween('21:00:01', '23:59:59') or now()->isBetween('00:00:00', '08:59:59'))
                {
                    if($user->BLID == 0)
                    {
                        User::where('accesstype','Cashier')
                        ->where('status','Active')
                        ->update([
                            'status' => 'Inactive',
                        ]);
                    }
                }
            }
            
               
               
                   
            
            

            #dd('Access Denied',now());
        }
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('h:i:s A');
        
        $request->authenticate();

        $request->session()->regenerate();

        if(auth()->user()->status != "Active"){

            
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
                'notes' => 'Login',
                'status'  => 'Login Failed. Inactive',
            ]);

            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('login')->with('failed','Account Inactive');
        }elseif(auth()->user()->accesstype == "Crew"){
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
                'notes' => 'Login',
                'status'  => 'Login Failed. Crew Account',
            ]);
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('login')->with('failed','Access Denied.');
        }else{
            if(auth()->user()->BLID == 1)
            {
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
                    'notes' => 'Login. Extended.hh',
                    'status'  => 'Success',
                ]);
            }
            else
            {
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
                    'notes' => 'Login',
                    'status'  => 'Success',
                ]);
            }
            
            return redirect()->intended(RouteServiceProvider::HOME);
        }
        
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('h:i:s A');
        
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
            'notes' => 'Login',
            'status'  => 'Logout',
        ]);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
