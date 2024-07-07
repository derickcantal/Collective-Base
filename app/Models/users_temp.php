<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class users_temp extends Model
{
    use HasFactory;

    protected $table = 'users_temp';  
    protected $primaryKey = 'userid';

    protected $dates = [
        'timerecorded',
    ];
    public function gettimerecordedAttribute($dates) {
        return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
    }

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
        'copied',
        'status',
    ];


}
