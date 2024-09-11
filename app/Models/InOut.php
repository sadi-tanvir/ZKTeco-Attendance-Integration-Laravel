<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InOut extends Model
{
    use HasFactory;

    protected $table='in_out_logs';

    protected $fillable = [
        'id', 'user_id', 'in_time', 'in_out', 'time_calc','date'
    ];

    protected $dates = [
        'timestamp'
    ];
}
