<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class sales_eod extends Model
{
    use HasFactory;

    protected $primaryKey = 'seodid';
    protected $table = 'sales_eod';  

    protected $dates = [
        'timerecorded',
    ];
    public function gettimerecordedAttribute($dates) {
        return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
    }

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
        'copied',
    ];
}
