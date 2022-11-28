<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\UserJobs;

class UserJobsRepository
{
	private $ujRepository;

	function __construct(UserJobs $ujRepository)
	{
		$this->ujRepository = $ujRepository;
	}

	public function getUserJobsByUserId($userId)
	{
		$userJobs = $this->ujRepository->select(
							'accounts.user_jobs.id',
							'accounts.jobs.name',
							'accounts.jobs.type',
						)
					   ->join('accounts.jobs', 'accounts.user_jobs.job_id', '=', 'accounts.jobs.id')
					   ->where('accounts.user_jobs.user_id', $userId)
					   ->first();

		return $userJobs;
	}
}