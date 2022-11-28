<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\PasswordThrottles;

class PasswordThrottlesRepository
{
	private $passwordThrottles;

	function __construct(PasswordThrottles $passwordThrottles)
	{
		$this->passwordThrottles = $passwordThrottles;
	}

	public function create($data)
	{
		$this->passwordThrottles->user_id = $data['user_id'];
		$this->passwordThrottles->request_count = $data['request_count'];
		$this->passwordThrottles->expires_on = date('Y-m-d H:i:s');
		$this->passwordThrottles->lock = $data['lock'];
		$this->passwordThrottles->ip = $data['ip'];
		$this->passwordThrottles->save();
		
		return $this->passwordThrottles;
	}

	public function reset($userId)
	{
		$this->passwordThrottles->where('user_id', $userId)->update([
			'request_count' => 0,
			'lock' => 0
		]);
	}

	public function increment($userId)
	{
		$this->passwordThrottles->where('user_id', $userId)->increment('request_count');
	}

	public function lock($userId, $ip)
	{
		$penalty = date('Y-m-d H:i:s', time() + config('throttle.limit.penalty'));
		$this->passwordThrottles->where('user_id', $userId)->update([
			'lock' => 1,
			'expires_on' => $penalty,
			'ip' => $ip
		]);

		return $penalty;
	}

	public function getThrottleByUserId($userId)
	{
		$throttle = $this->passwordThrottles->select(
							'id',
							'user_id',
							'request_count',
							'expires_on',
							'lock',
							'ip',
						)
					   ->where('user_id', $userId)
					   ->first();

		return $throttle;
	}
}