<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Renter extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'renters';  
    protected $primaryKey = 'rentersid';

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
        'BLID',
        'accesstype',
        'created_by',
        'updated_by',
        'timerecorded',
        'mod',
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
