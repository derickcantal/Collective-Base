<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class RentalPayments extends Model
{
    use HasFactory;

    protected $table = 'Rental_Payments';  

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
        'created_by',
        'status',
    ];

    protected $primaryKey = 'rpid';
}
