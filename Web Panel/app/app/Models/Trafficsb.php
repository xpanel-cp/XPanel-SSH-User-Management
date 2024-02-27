<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trafficsb extends Model
{
    use HasFactory;
    protected $primaryKey = 'port_sb';
    protected $fillable = [
        'port_sb',
        'sent_sb',
        'received_sb',
        'total_sb'
    ];
}
