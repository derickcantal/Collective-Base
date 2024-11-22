<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use \Carbon\Carbon;

class Renter extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'mysql';
    protected $table = 'collective-base.renters';  
    protected $primaryKey = 'rentersid';

    protected $dates = [
        'timerecorded',
    ];
    public function gettimerecordedAttribute($dates) {
        return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
    }

    protected $fillable = [
        'avatar',
        'username',
        'email',
        'password',
        'firstname',
        'middlename',
        'lastname',
        'birthdate',
        'mobile_primary',
        'mobile_secondary',
        'homeno',
        'branchid',
        'branchname',
        'cabid',
        'cabinetname',
        'duedate',
        'rnotes',
        'BLID',
        'accesstype',
        'created_by',
        'updated_by',
        'timerecorded',
        'mod',
        'copied',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthdate' => 'date',
    ];
}
