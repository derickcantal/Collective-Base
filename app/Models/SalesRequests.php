<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class SalesRequests extends Model
{
    use HasFactory;

        protected $table = 'sales_requests';  
        protected $primaryKey = 'salesrid';

        protected $fillable = [
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

        protected $casts = [
            'rstartdate' => 'datetime',
            'renddate' => 'datetime',
            'timerecorded' => 'datetime:Y-m-d H:i A'
        ];


    
}
