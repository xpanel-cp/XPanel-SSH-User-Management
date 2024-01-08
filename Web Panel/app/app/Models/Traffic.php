<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traffic extends Model
{
    use HasFactory;
    protected $fillable = [
        'username',
        'download',
        'upload',
        'total'
    ];
    public function users()
    {
        return $this->belongsTo(Users::class);
    }
    public function scopeActiveUsers($query)
    {
        return $query
            ->join('users', 'traffic.username', '=', 'users.username')
            ->where('users.status', 'active')
            ->orderByRaw('CAST(traffic.total AS SIGNED) DESC')
            ->select('traffic.*');
    }
}
