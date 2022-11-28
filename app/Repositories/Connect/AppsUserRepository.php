<?php

namespace App\Repositories\Connect;

use App\Models\Connect\AppsUser;

class AppsUserRepository
{
    public function __construct(private AppsUser $users)
    {
    }

    public function getListByUserId($userId)
    {
        $data = $this->users->query()
            ->where('user_id', $userId)
            ->get();

        return $data;
    }
}
