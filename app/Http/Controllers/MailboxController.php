<?php

namespace App\Http\Controllers;

use App\Models\mailbox;
use App\Models\user_login_log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Validation\Rules;
use \Carbon\Carbon;

class MailboxController extends Controller
{

    public function userlog($notes,$status){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        
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
            'notes' => $notes,
            'status'  => $status,
        ]);
    }

    public function loaddata(){
        $mailbox = mailbox::query()
                    ->orderBy('username','asc')
                    ->paginate(5);


        $notes = 'Mailbox';
        $status = 'Success';
        $this->userlog($notes,$status);
        
        return view('mailbox.index',compact('mailbox'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function createdata(){
        return view('mailbox.create');

    }

    public function storedata($request){
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:'.mailbox::class],
            'email_other' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.mailbox::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'max:255', 'unique:'.mailbox::class],
        ]);

        $mailbox = mailbox::create([
            'username' => $request->username . '@collectivebaseph.art',
            'password' => Hash::make($request->password),
            'name' => $request->fullname,
            'maildir' => 'collectivebaseph.art/' . $request->username . '/',
            'quota' => 0,
            'local_part' => $request->username,
            'domain' => 'collectivebaseph.art',
           
            'active' => 1,
            'phone' => $request->phone,
            'email_other' => $request->email_other,
  
      
     
            'smtp_active' => 1,
            'created_by' => auth()->user()->email,
            'updated_by' => 'Null',
            'timerecorded' => $timenow,
            'posted' => 'N',
            'modi' => 0,
        ]);

        if ($mailbox) {
            //query successful

            $notes = 'Mailbox. Create ' . $request->username;
            $status = 'Success';
            $this->userlog($notes,$status);

            return redirect()->route('mailbox.index')
                        ->with('success','Mailbox Account created successfully.');
        }else{
            $notes = 'Mailbox. Create ' . $request->username;
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('mailbox.create')
                        ->with('failed','Mailbox Account creation failed');
        }
    }

    public function search()
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->loaddata();
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->loaddata();
            }
        }else{
            return redirect()->route('dashboard.index');
        }
        //$mailbox = mailbox::all();
        //dd($mailbox);
        //return view('mailbox.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->createdata();
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->createdata();
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->storedata($request);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->storedata($request);
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
