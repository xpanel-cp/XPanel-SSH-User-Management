<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ipadapter extends Model
{
    use HasFactory;
    protected $fillable = [
        'email_cf',
        'token_cf',
        'sub_cf',
        'status_chanched',
        'status_active',
        'log_change_hourly',
        'log_change_traffic'
    ];
}
