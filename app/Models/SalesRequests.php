<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class SalesRequests extends Model
{
    use HasFactory;

        protected $table = 'sales_requests';  
        protected $primaryKey = 'salesrid';

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
            'created_by',
            'updated_by',
            'timerecorded',
            'posted',
            'mod',
            'status',
        ];
    
}
