<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sales_eod extends Model
{
    use HasFactory;

    protected $primaryKey = 'seodid';
    protected $table = 'sales_eod';  

    protected $fillable = [
        'seodid',
        'branchid',
        'branchname',
        'totalsales',
        'rentalpayments',
        'requestpayments',
        'otherexpenses',
        'totalcash',
        'notes',
        'created_by',
        'updated_by',
        'timerecorded',
        'posted',
    ];
}
