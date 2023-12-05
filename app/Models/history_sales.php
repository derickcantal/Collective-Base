<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class history_sales extends Model
{
    use HasFactory;

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
        'status',
    ];

    protected $primaryKey = 'salesid';
}
