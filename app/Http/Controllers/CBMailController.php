<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\CBMail;


class CBMailController extends Controller
{
   
    public function index()
    {
        return view('cbmail.cbmail_content');
        
    }

    public function sendmail(Request $request){

        Mail::to($request->email)->send(new CBMail([
            'title' => $request->title,
            'body' => $request->body,
        ]));

        return redirect()->route('cbmail.index')
                                ->with('success','Message Sent successfully.');
    }
}
