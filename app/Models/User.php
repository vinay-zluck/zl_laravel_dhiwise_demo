<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $table = 'users';

    const PLATFORM = [
        'admin' => 1,
        'device' => 2,
    ];

    const MAX_LOGIN_RETRY_LIMIT = 3;
    const LOGIN_REACTIVE_TIME = 1;

    const FORGOT_PASSWORD_WITH = [
            'link' => [
                'email' => true,
                'sms' => false
            ],
            'expireTime' => '5'
        ];
    
    const LOGIN_ACCESS = [
        'User' => [self::PLATFORM['device'],],
        'Admin' => [self::PLATFORM['admin'],],
    ];

    protected $fillable = [
                            'username',
                        'password',
                        'email',
                        'name',
                        'is_active',
                        'created_at',
                        'updated_at',
                        'added_by',
                        'updated_by',
            'login_reactive_time',
'login_retry_limit',
'reset_password_expire_time',
'reset_password_code',
'email_verified_at',
];

protected $hidden = [
    'password',
];

protected $casts = [
                'username' => 'string',
                'password' => 'string',
                'email' => 'string',
                'name' => 'string',
                'is_active' => 'boolean',
                'created_at' => 'date',
                'updated_at' => 'date',
                'added_by' => 'integer',
                'updated_by' => 'integer',
    'login_reactive_time' => 'datetime',
'login_retry_limit' => 'integer',
        'reset_password_expire_time' => 'datetime',
        'reset_password_code' => 'string',
        'email_verified_at' => 'datetime',
];
}
