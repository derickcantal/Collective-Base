<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class branch extends Model
{
    use HasFactory;

    protected $primaryKey = 'branchid';
    protected $table = 'branch';

    protected $fillable = [
        'branchname',
        'branchaddress',
        'branchcontact',
        'branchemail',
        'cabinetcount',
        'created_by',
        'updated_by',
        'posted',
        'mod',
        'status',
    ];
}
