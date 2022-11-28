<?php

namespace App\Repositories\Accounts;

use Illuminate\Support\Facades\Hash;
use App\Models\Accounts\Users;
use App\Models\Accounts\UserDevices;

class UsersRepository
{
    private $users;

    function __construct(Users $users)
    {
        $this->users = $users;
    }

    public function create($user)
    {
        $this->users->username = $user['username'];
        $this->users->phone = $user['phone'];
        $this->users->phone_code = $user['phonecode'];
        $this->users->password = Hash::make(config('hashing.default'));
        $this->users->status = 1;
        $this->users->referral_by = null;
        $this->users->save();

        return $this->users;
    }

    public function update($data, $userId)
    {
        $this->users->where('id', $userId)->update($data);
    }

    public function getUserByUsername($username)
    {
        $user = $this->users->findForPassport($username);

        return $user;
    }

    public function getUserById($id)
    {
        $user = $this->users->where('id', $id)->first();

        return $user;
    }

    public function getUserByField($field, $email)
    {
        $user = $this->users->select('id')->where($field, $email)->first();

        return $user;
    }

    public function getUserSearch($data)
    {
        $user = $this->users
                    ->where('phone', $data)
                    ->orWhere('nickname', $data)
                    ->orWhere('username', $data)
                    ->first();

        return $user;
    }

    public function getUserAllByField($field, $email)
    {
        $user = $this->users->where($field, $email)->first();

        return $user;
    }

    public function getAccessTokens(Users $user, $type = 'current')
    {
        if ($type == 'current')
            $token = $user->token();
        else
            $token = $user->tokens;

        return $token;
    }

    public function createToken($data)
    {
        $user = $data['user'];
        $token =  $user->createToken($user->username . '-token-' . time());
        $token->token->update([
            'ip' => $data['ip'],
            'device' => $data['device'],
            'device_id' => $data['device_id'],
            'location' => $data['location'],
        ]);

        if (is_null($user->date_activated))
            $extra['date_activated'] = date('Y-m-d H:i:s');

        $extra['last_login'] = date('Y-m-d H:i:s');
        $this->users->where('id', $user->id)->update($extra);

        return $token;
    }

    public function revokeTokens(Users $user, $type = 'all')
    {
        $accessTokens = $this->getAccessTokens($user, 'all');
        foreach ($accessTokens as $token) {
            if ($type == 'all') {
                $token->revoke();
            } else {
                if ($type == $token->id)
                    $token->revoke();
                else
                    continue;
            }
        }

        return true;
    }

    public function validatePasswordHash($input, $hash)
    {
        return Hash::check($input, $hash);
    }

    public function updatePassword($newPassword, $userId)
    {
        $this->users->where('id', $userId)->update(['password' => Hash::make($newPassword)]);
    }

    public function search($username)
    {
        $users = $this->users->select(
            'accounts.users.id',
            'accounts.users.name',
            'accounts.users.phone',
            'accounts.users.phone_code',
            'accounts.users.avatar',
            'accounts.user_informations.active'
        )
            ->join('accounts.user_informations', 'accounts.user_informations.user_id', '=', 'accounts.users.id')
            ->where('accounts.users.username', 'like', $username . '%')
            ->get();

        return $users;
    }

    public function registerDevice($data)
    {
        $flight = UserDevices::updateOrCreate($data);
        return $flight;
    }
}
