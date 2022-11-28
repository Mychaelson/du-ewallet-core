<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\Reasons;

class ReasonsRepository
{
	private $reasons;

	function __construct(Reasons $reasons)
	{
		$this->reasons = $reasons;
	}

	public function getLatestReasonByUserId($userId)
	{
		$reason = $this->reasons->select(
						'accounts.reasons.content',	
						'accounts.reasons.subject',	
						'accounts.reasons.field',
						'backoffice.user.name as validated_by'	
					)
					->join('backoffice.user', 'accounts.reasons.by', '=', 'backoffice.user.id')
					->where('accounts.reasons.user_id', $userId)
					->orderBy('accounts.reasons.updated_at', 'desc')
					->first();

		return $reason;
	}
}