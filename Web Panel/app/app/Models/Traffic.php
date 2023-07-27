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
}
