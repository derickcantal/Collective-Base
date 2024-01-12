<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class history_sales_requests extends Model
{
    use HasFactory;

    protected $table = 'history_sales_requests';   
    protected $primaryKey = 'salesrid';

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
        'created_by',
        'updated_by',
        'timerecorded',
        'posted',
        'mod',
        'status',
    ];

}
