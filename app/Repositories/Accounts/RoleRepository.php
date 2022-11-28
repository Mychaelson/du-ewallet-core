<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\Roles;
use App\Models\Accounts\RoleUser;

class RoleRepository
{
	private $roles;
	private $roleUser;

	function __construct(Roles $roles, RoleUser $roleUser)
	{
		$this->roles = $roles;
		$this->roleUser = $roleUser;
	}

	public function getUserRoleByUserId($userId)
	{
		$roles = $this->roleUser->select(
					'accounts.role_user.id',
					'accounts.role_user.user_id',
					'accounts.role_user.role_id',
					'accounts.roles.name'
				)
				->join('accounts.roles', 'accounts.role_user.role_id', '=', 'accounts.roles.id')
				->where('accounts.role_user.user_id', $userId)
				->first();

		return $roles;
	}
}