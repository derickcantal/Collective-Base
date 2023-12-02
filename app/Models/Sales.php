<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'salesname',
        'salesavatar',
        'cabinetid',
        'cabinetname',
        'productname',
        'origprice',
        'srp',
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
