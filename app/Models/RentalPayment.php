<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class RentalPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'userid',
        'username',
        'firstname',
        'lastname',
        'rpamount',
        'rppaytype',
        'rpmonthyear',
        'rpnotes',
        'branchid',
        'branchname',
        'avatarproof',
        'status',
    ];

    protected $primaryKey = 'rpid';
}
