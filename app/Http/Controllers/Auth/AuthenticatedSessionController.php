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

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('h:i:s A');
        
        $timezone = 'Asia/Manila';

        // today at 1030pm
        $today = Carbon::parse('today 10:30pm', $timezone);

        // tomorrow 930am
        $tomorrow = Carbon::parse('tomorrow 4:30am', $timezone);

        // Now
        $now = Carbon::now($timezone);

        if ($now->gte($today) && $now->lte($tomorrow)) {
            //dd('Deactivating Cashier accounts.');
        }else{
            //dd('Active Sessions: '.$timenow);
        }
        



        $request->authenticate();

        $request->session()->regenerate();

        if(auth()->user()->status != "Active"){
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('login')->with('failed','Account Inactive');
        }elseif(auth()->user()->accesstype == "Crew"){
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('login')->with('failed','Access Denied.');
        }else{

            return redirect()->intended(RouteServiceProvider::HOME);
        }
        
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
