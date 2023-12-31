<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $table = 'sales';  

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
        'posted',
        'mod',
        'status',
    ];

    protected $primaryKey = 'salesid';
}
