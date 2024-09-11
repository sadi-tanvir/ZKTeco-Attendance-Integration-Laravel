<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table='attendances';

    protected $fillable = [
        'uid', 'user_id', 'state', 'timestamp', 'type'
    ];

}
