<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cabinet extends Model
{
    use HasFactory;

    protected $primaryKey = 'cabid';
    protected $table = 'cabinet';

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
        'status',
    ];
}
