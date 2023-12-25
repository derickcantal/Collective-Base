<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';
    
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
        'status',
    ];

    protected $primaryKey = 'attid';

}
