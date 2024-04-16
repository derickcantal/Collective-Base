<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class branchlist extends Model
{
    use HasFactory;

    protected $primaryKey = 'BLID';
    protected $table = 'branchlist';

    protected $fillable = [
            'userid',
            'branchid',
            'accesstype',
            'timerecorded',
            'posted',
            'created_by',
            'updated_by',
            'mod',
            'status',
    ];
}
