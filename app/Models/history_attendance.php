<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class history_attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'userid',
        'username',
        'firstname',
        'lastname',
        'branchid',
        'branchname',
        'attnotes',
        'status',
    ];

    protected $primaryKey = 'attid';
}
