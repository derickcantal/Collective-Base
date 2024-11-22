<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class branch extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $primaryKey = 'branchid';
    protected $table = 'collective-base.branch';

    protected $dates = [
        'timerecorded',
    ];
    public function gettimerecordedAttribute($dates) {
        return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
    }

    protected $fillable = [
        'branchname',
        'branchaddress',
        'branchcontact',
        'branchemail',
        'cabinetcount',
        'created_by',
        'updated_by',
        'timerecorded',
        'posted',
        'mod',
        'copied',
        'status',
    ];
}
