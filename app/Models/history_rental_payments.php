<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class history_rental_payments extends Model
{
    use HasFactory;

    protected $table = 'history_rental_payments';
    protected $primaryKey = 'rpid';

    protected $fillable = [
        'userid',
        'username',
        'firstname',
        'lastname',
        'rpamount',
        'rpbal',
        'rppaytype',
        'rpmonthyear',
        'rpnotes',
        'branchid',
        'branchname',
        'avatarproof',
        'created_by',
        'updated_by',
        'timerecorded',
        'posted',
        'fully_paid',
        'mod',
        'status',
    ];

}
