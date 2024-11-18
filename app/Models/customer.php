<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'cid';
    protected $table = 'customers';

    protected $dates = [
        'timerecorded',
    ];
    
    public function gettimerecordedAttribute($dates) {
        return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
    }

    protected $fillable = [
            'cid',
            'avatar',
            'username',
            'firstname',
            'middlename',
            'lastname',
            'birthdate',
            'email', 
            'mobile_primary',
            'mobile_secondary',
            'homeno',
            'branchid',
            'branchname',
            'cabid',
            'cabinetname',
            'BLID',
            'password',
            'accesstype',
            'cpid',
            'tpointdatelast',
            'tpointslast', 
            'tpoints',
            'timerecorded',
            'created_by',
            'updated_by',
            'mod',
            'copied',
            'status',
    ];
}
