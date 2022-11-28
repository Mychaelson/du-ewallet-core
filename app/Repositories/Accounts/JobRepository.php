<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\Jobs;
use App\Models\Accounts\Users;
use App\Models\Accounts\UserJobs;

class JobRepository
{
	private $jobs;
  private $users;
  private $userJobs;

	function __construct(Jobs $jobs, Users $users, UserJobs $userJobs )
	{
		$this->jobs = $jobs;
    $this->users = $users;
    $this->userJobs = $userJobs;
	}

  public function getJobs () {
    $arrayOfJobs = $this->jobs::all()->toArray();

    foreach ($arrayOfJobs as $key => $value) {
      $nameOfJob = json_decode($value['name'], true);
      $arrayOfJobs[$key]['name'] = $nameOfJob[app()->getLocale()];
    }

    return $arrayOfJobs;
  }

  public function addJob ($user_id, $job_id, $company) {
    $data = [
      'user_id' => $user_id,
      'job_id' => $job_id,
      'company' => $company
    ];
    
    $this->userJobs->insert($data);

    $job = $this->userJobs
                ->leftJoin('accounts.jobs', 'user_jobs.job_id', '=', 'accounts.jobs.id')
                ->where('user_id', $user_id)->first();

    $nameOfJobCategory = json_decode($job->name, true);
    $job->name = $nameOfJobCategory[app()->getLocale()];
    return $job;
  }
}