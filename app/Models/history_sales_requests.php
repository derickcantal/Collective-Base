<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class history_sales_requests extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'collective-base.history_sales_requests';   
    protected $primaryKey = 'salesrid';

    protected $dates = [
        'timerecorded',
    ];
    public function gettimerecordedAttribute($dates) {
        return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
    }

    protected $fillable = [
        'salesrid',
        'branchid',
        'branchname',
        'cabinetid',
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
