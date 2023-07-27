<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;
    protected $fillable = [
        'username',
        'password',
        'email',
        'mobile',
        'multiuser',
        'start_date',
        'end_date',
        'date_one_connect',
        'customer_user',
        'status',
        'traffic',
        'referral',
        'desc'
    ];
    public function traffics() {
        return $this->hasMany(Traffic::class, 'username', 'username');
    }
}
