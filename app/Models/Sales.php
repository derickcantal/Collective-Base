<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $table = 'sales';  
    protected $primaryKey = 'salesid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'salesname',
        'salesavatar',
        'cabid',
        'cabinetname',
        'productname',
        'qty',
        'origprice',
        'srp',
        'total',
        'grandtotal',
        'payavatar',
        'paytype',
        'payref',
        'userid',
        'username',
        'accesstype',
        'branchid',
        'branchname',
        'collected_status',
        'returned',
        'snotes',
        'created_by',
        'updated_by',
        'timerecorded',
        'posted',
        'mod',
        'status',
    ];

}
