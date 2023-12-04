<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales_Requests extends Model
{
    use HasFactory;

            protected $fillable = [
                'salesrid',
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
                'userid',
                'lastname',
                'updated_by',
                'status',
            ];
        
            protected $primaryKey = 'salesrid';
}
