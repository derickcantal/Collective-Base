<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class RenterRequests extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'collective-base.sales_requests';  
    protected $primaryKey = 'salesrid';

    protected $dates = [
        'timerecorded',
    ];
    public function gettimerecordedAttribute($dates) {
        return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
    }

    protected $fillable = [
        'branchid',
        'branchname',
        'cabid',
        'cabinetname',
        'totalsales',
        'totalcollected',
        'avatarproof',
        'rnotes',
        'userid',
        'firstname',
        'lastname',
        'rstartdate',
        'renddate',
        'created_by',
        'updated_by',
        'timerecorded',
        'timerecorded_c',
        'posted',
        'mod',
        'copied',
        'status',
    ];

}
