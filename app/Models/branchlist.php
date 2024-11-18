<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class branchlist extends Model
{
    use HasFactory;

    protected $primaryKey = 'BLID';
    protected $table = 'branchlist';

    protected $dates = [
        'timerecorded',
    ];
    public function gettimerecordedAttribute($dates) {
        return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
    }

    protected $fillable = [
            'userid',
            'branchid',
            'accesstype',
            'timerecorded',
            'cabcount',
            'posted',
            'created_by',
            'updated_by',
            'mod',
            'copied',
            'status',
    ];
}
