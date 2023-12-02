<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UsersController extends Controller
{
     public function displayall()
    {
        $user = User::wherenot('accesstype', 'Leesee')->get();
        
        return view('users.edit', ['user' => $user]);
    }
}
