<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Renters extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';  
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'avatar',
        'username',
        'email',
        'password',
        'firstname',
        'middlename',
        'lastname',
        'birthdate',
        'branchid',
        'branchname',
        'cabid',
        'cabinetname',
        'accesstype',
        'created_by',
        'updated_by',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthdate' => 'date',
    ];

    protected $primaryKey = 'userid';
}
