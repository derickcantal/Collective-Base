<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAvatarRequest;
use Illuminate\Http\Request;

class AvatarController extends Controller
{
   public function update(UpdateAvatarRequest $request){
        $path = Storage::disk('public')->put('avatars',$request->file('avatar'));
        // $path = $request->file('avatar')->store('avatars','public');
        
        if($oldavatar = $request->user()->avatar){
            Storage::disk('public')->delete($oldavatar);
        }
        
        auth()->user()->update(['avatar' => $path]);

        return redirect(route('profile.edit'))->with(['message', 'Avatar is Changed.']);
   }
}
