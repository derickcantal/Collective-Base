<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use \Carbon\Carbon;

class renter_remittance extends Model
{
     use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'mysql';
    protected $table = 'collective-base.renter_remittance';  
    protected $primaryKey = 'rrid';

    protected $dates = [
        'timerecorded',
    ];
    public function gettimerecordedAttribute($dates) {
        return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
    }

    protected $fillable = [
        'rrid',
        'rentersid',
        'username',
        'firstname',
        'lastname',
        'wremitstart',
        'wremitend',
        'totalsales',
        'totalremit',
        'totalbal',
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
