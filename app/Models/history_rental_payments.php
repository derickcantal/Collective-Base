<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class history_rental_payments extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'collective-base.history_rental_payments';
    protected $primaryKey = 'rpid';

    protected $dates = [
        'timerecorded',
    ];
    public function gettimerecordedAttribute($dates) {
        return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
    }

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
        'copied',
        'status',
    ];

}
