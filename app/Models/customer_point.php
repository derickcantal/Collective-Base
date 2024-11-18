<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer_point extends Model
{
    use HasFactory;

    protected $primaryKey = 'cpid';
    protected $table = 'customers_point';

    protected $dates = [
        'timerecorded',
    ];

    protected $fillable = [
            'cid',
            'username',
            'firstname',
            'middlename',
            'lastname',
            'email', 
            'accesstype',
            'tpointdatelast',
            'tpointslast',
            'tpoints',
            'timerecorded',
            'created_by',
            'updated_by',
            'mod',
            'copied',
            'status',
    ];
}
