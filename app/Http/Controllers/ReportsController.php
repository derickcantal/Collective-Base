<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function displayall()
    {
        return view('reports.edit');
    }
}
