<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class rental_active_month extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'collective-base.rental_active_month';  
    protected $primaryKey = 'ramid';

    protected $dates = [
        'timerecorded',
    ];
    public function gettimerecordedAttribute($dates) {
        return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
    }

    protected $fillable = [
        'rpmonth',
        'rpyear',
        'rpnotes',
        'created_by',
        'updated_by',
        'timerecorded',
        'posted',
        'mod',
        'copied',
        'status',
    ];
}
