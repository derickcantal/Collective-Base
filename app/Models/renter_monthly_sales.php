<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class renter_monthly_sales extends Model
{
    protected $connection = 'mysql';
    protected $table = 'collective-base.renter_monthly_sales';  
    protected $primaryKey = 'rmsid';

    protected $dates = [
        'timerecorded',
    ];
    public function gettimerecordedAttribute($dates) {
        return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
    }

    protected $fillable = [
        'rmsid',
        'rentersid',
        'username',
        'firstname',
        'lastname',
        'rpmonth',
        'rpyear',
        'totalsales',
        'rpnotes',
        'branchid',
        'branchname',
        'cabid',
        'cabinetname',
        'avatarproof',
        'created_by',
        'updated_by',
        'timerecorded',
        'posted',
        'fully_paid',
        'mod',
        'copied',
        'status',
    ];
}
