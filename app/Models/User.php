<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions, ExcludeSystemFillable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'last_login_date',
        'default_role_id',
        'creator_id',
        'status_id',
    ];

    protected $systemFillable = [
        'status_id',
        'first_name',
        'middle_name',
        'last_name',
        'two_factor_valid',
        'last_login_date',
        'two_factor_expires_at',
        'two_factor_code',
        'email_verified_at',
        'api_token',
        'session_id',
        'is_session_valid',
        'is_online',
        'remember_token',
        'theme',
        'default_role_id',
        'creator_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
