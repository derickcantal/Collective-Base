<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class history_attendance extends Model
{
    use HasFactory;

    protected $table = 'history_attendance';
    protected $primaryKey = 'attid';

    protected $fillable = [
        'userid',
        'username',
        'firstname',
        'lastname',
        'branchid',
        'branchname',
        'attnotes',
        'created_by',
        'updated_by',
        'timerecorded',
        'posted',
        'mod',
        'status',
    ];

}
