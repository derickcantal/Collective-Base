<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class history_sales extends Model
{
    use HasFactory;

    protected $table = 'history_sales';      

    protected $fillable = [
        'salesname',
        'salesavatar',
        'cabinetid',
        'cabinetname',
        'productname',
        'qty',
        'origprice',
        'srp',
        'total',
        'grandtotal',
        'userid',
        'username',
        'accesstype',
        'branchid',
        'branchname',
        'collected_status',
        'snotes',
        'created_by',
        'updated_by',
        'posted',
        'mod',
        'status',
    ];

    protected $primaryKey = 'salesid';
}
