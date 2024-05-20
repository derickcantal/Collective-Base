<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_login_log extends Model
{
    use HasFactory;
            protected $table = 'user_login_log';  
            protected $primaryKey = 'ullid';

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
                'status',
            ];
            
}
