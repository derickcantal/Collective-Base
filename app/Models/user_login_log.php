<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class user_login_log extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
            protected $table = 'collective-base.user_login_log';  
            protected $primaryKey = 'ullid';

            protected $dates = [
                'timerecorded',
            ];
            public function gettimerecordedAttribute($dates) {
                return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
            }

            protected $fillable = [
                'userid',
                'username',
                'firstname',
                'middlename',
                'lastname',
                'email',
                'branchid',
                'branchname',
                'accesstype',
                'timerecorded',
                'created_by',
                'updated_by',
                'mod',
                'notes',
                'copied',
                'status',
            ];
            
}
