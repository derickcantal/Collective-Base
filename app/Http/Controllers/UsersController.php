<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UsersController extends Controller
{
     public function displayall()
    {
        $user = User::wherenot('accesstype', 'Leesee')->get();
        
        return view('users', ['user' => $user]);
    }
}
