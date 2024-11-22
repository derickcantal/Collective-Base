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

    public function editdata($username){
        //dd($username);
        $umailbox = mailbox::where('username', $username)
                        ->first();

        return view('mailbox.edit',['mailbox' => $umailbox]);
    }

    public function destroydata($username){
        $umailbox = mailbox::where('username', $username)
                        ->first();

        if($umailbox->active == 1)
        {
            mailbox::where('username', $umailbox->username)
            ->update([
            'active' => 0,
            ]);

            $notes = 'Mailbox. Deactivate. ' . $umailbox->fullname;
            $status = 'Success';
            $this->userlog($notes,$status);

            return redirect()->route('mailbox.index')
                ->with('success','Mail Account Decativated successfully');
        }
        elseif($umailbox->active == 0)
        {
            mailbox::where('username', $umailbox->username)
            ->update([
            'active' => 1,
            ]);


            $notes = 'Mailbox. Activate. ' . $umailbox->fullname;
            $status = 'Success';
            $this->userlog($notes,$status);

            return redirect()->route('mailbox.index')
                ->with('success','Mail Account Activated successfully');
        }

        

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

    public function updatedata($request, $username){
        $umailbox = mailbox::where('username', $username)
                        ->first();
        //dd($request,$umailbox);

        $mod = 0;
        $mod = $umailbox->modi;

        if(empty($request->password)){
            $mailboxupdate = mailbox::where('username', $umailbox->username)->update([
                'name' => $request->name,
                'active' => $request->active,
                'phone' => $request->phone,
                'email_other' => $request->email_other,
                'updated_by' => auth()->user()->email,
                'modi' => $mod + 1,
                ]);
        }elseif ($request->password == $request->password_confirmation){
            $mailboxupdate = mailbox::where('username', $umailbox->username)->update([
                'password' => Hash::make($request->password),
                'name' => $request->name,
                'active' => $request->active,
                'phone' => $request->phone,
                'email_other' => $request->email_other,
                'updated_by' => auth()->user()->email,
                'modi' => $mod + 1,
                ]);
        }
       
        if($mailboxupdate){
            $notes = 'Mailbox. Update. ' . $umailbox->username;
            $status = 'Success';
            $this->userlog($notes,$status);

            return redirect()->route('mailbox.index')
                        ->with('success','Mail Account updated successfully');
        }else{
            $notes = 'Mailbox. Update. ' . $umailbox->username;
            $status = 'Failed';
            $this->userlog($notes,$status);

            return redirect()->route('mailbox.index')
                        ->with('failed','Mail Account updated failed');
            }
    }

    public function search(Request $request)
    {
        $stat = '';
        if($request->search == 'active' or $request->search == 'Active'){
            $stat = 1;
            $mailbox = mailbox::query()
                ->where(function(Builder $builder) use($request,$stat){
                    $builder->where('username','like',"%{$request->search}%")
                            ->orWhere('name','like',"%{$request->search}%")
                            ->orWhere('phone','like',"%{$request->search}%")
                            ->orWhere('email_other','like',"%{$request->search}%")
                            ->orWhere('created_by','like',"%{$request->search}%")
                            ->orWhere('updated_by','like',"%{$request->search}%")
                            ->orWhere('active','like',"%{$stat}%");
                })
                ->orderBy('username',$request->orderrow)
                ->paginate($request->pagerow);
        }elseif($request->search == 'inactive' or $request->search == 'Inactive'){
            $stat = 0;
            $mailbox = mailbox::query()
                ->where(function(Builder $builder) use($request,$stat){
                    $builder->where('username','like',"%{$request->search}%")
                            ->orWhere('name','like',"%{$request->search}%")
                            ->orWhere('phone','like',"%{$request->search}%")
                            ->orWhere('email_other','like',"%{$request->search}%")
                            ->orWhere('created_by','like',"%{$request->search}%")
                            ->orWhere('updated_by','like',"%{$request->search}%")
                            ->orWhere('active','like',"%{$stat}%");
                })
                ->orderBy('username',$request->orderrow)
                ->paginate($request->pagerow);
        }else{
            $mailbox = mailbox::query()
                ->where(function(Builder $builder) use($request,$stat){
                    $builder->where('username','like',"%{$request->search}%")
                            ->orWhere('name','like',"%{$request->search}%")
                            ->orWhere('phone','like',"%{$request->search}%")
                            ->orWhere('created_by','like',"%{$request->search}%")
                            ->orWhere('updated_by','like',"%{$request->search}%");
                })
                ->orderBy('username',$request->orderrow)
                ->paginate($request->pagerow);
        }
        return view('mailbox.index',compact('mailbox'))
            ->with('i', (request()->input('page', 1) - 1) * $request->pagerow);
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
    public function edit(string $username)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->editdata($username);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->editdata($username);
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $username)
    {
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->updatedata($request,$username);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->updatedata($request,$username);
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $username)
    {
        
        if(auth()->user()->status =='Active'){
            if(auth()->user()->accesstype =='Cashier'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Renters'){
                return redirect()->route('dashboard.index');
            }elseif(auth()->user()->accesstype =='Supervisor'){
                return $this->destroydata($username);
            }elseif(auth()->user()->accesstype =='Administrator'){
                return $this->destroydata($username);
            }
        }else{
            return redirect()->route('dashboard.index');
        }
    }
}
