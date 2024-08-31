<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthenticationLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'authenticatable_id',
        'authenticatable_type',
        'ip_address',
        'user_agent',
        'login_at',
        'logout_at',
        'login_successful',
    ];

    public function authenticatable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
