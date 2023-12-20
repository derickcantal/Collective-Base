<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenterRequests extends Model
{
    use HasFactory;

    protected $table = 'sales_requests';  

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
        'updated_by',
        'status',
    ];

    protected $primaryKey = 'salesrid';
}
