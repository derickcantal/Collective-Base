<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class LeeseeController extends Controller
{
     public function displayall()
    {
        $leesee = User::where('accesstype', 'Leesee')->get();
        
        return view('leesee.edit', ['leesee' => $leesee]);
    }
}
