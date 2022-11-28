<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Users extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'accounts.users';
    protected $fillable = ['username'];
    protected $hidden = ['password'];
    protected $casts = [
        'is_active_password' => 'boolean',
        'email_verified' => 'boolean',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }
}
