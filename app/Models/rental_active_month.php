<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rental_active_month extends Model
{
    use HasFactory;

    protected $table = 'rental_active_month';  
    protected $primaryKey = 'ramid';

    protected $fillable = [
        'rpmonth',
        'rpyear',
        'rpnotes',
        'created_by',
        'updated_by',
        'timerecorded',
        'posted',
        'mod',
        'status',
    ];
}
