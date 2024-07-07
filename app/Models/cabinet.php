<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class cabinet extends Model
{
    use HasFactory;

    protected $primaryKey = 'cabid';
    protected $table = 'cabinet';

    protected $dates = [
        'timerecorded',
    ];
    public function gettimerecordedAttribute($dates) {
        return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
    }

    protected $fillable = [
        'cabinetname',
        'cabinetprice',
        'branchid',
        'branchname',
        'userid',
        'email',
        'created_by',
        'updated_by',
        'timerecorded',
        'rpmonth',
        'rpyear',
        'fully_paid',
        'posted',
        'mod',
        'copied',
        'status',
    ];
}
