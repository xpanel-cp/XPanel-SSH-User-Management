<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;
    protected $fillable = [
        'ssh_port',
        'tls_port',
        't_token',
        't_id',
        'language',
        'multiuser',
        'ststus_multiuser',
        'home_url'
    ];
}
