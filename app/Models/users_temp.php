<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class users_temp extends Model
{
    use HasFactory;

    protected $table = 'users_temp';  
    protected $primaryKey = 'userid';

    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'birthdate',
        'branchid',
        'branchname',
        'accesstype',
        'created_by',
        'updated_by',
        'timerecorded',
        'mod',
        'status',
    ];


}
