<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\OneTimePasswords;

class OTPRepository
{
	private $otp;

	function __construct(OneTimePasswords $otp)
	{
		$this->otp = $otp;
	}

	public function generate($username, $action)
	{
		$this->otp->username = $username;
		$this->otp->action = $action;
		$this->otp->token = random_int(100000, 999999);
		$this->otp->tries = 0;
		$this->otp->expires_at = date('Y-m-d H:i:s', time() + 300);
		$this->otp->save();

		return $this->otp;
	}

	public function validate($username, $action, $token)
	{
		$validate = $this->otp->select('id', 'tries', 'expires_at')
					->where('username', $username)
					->where('action', $action)
					->where('token', $token)
					->first();

		return $validate;
	}

	public function delete($id)
	{
		$delete = $this->otp->where('id', $id)->delete();
					
		return true;
	}

	public function increment($id)
	{
		$increment = $this->otp->where('id', $id)->increment('tries');
					
		return true;
	}

	public function getOtpByUsername($username)
	{
		$otp = $this->otp->select('id', 'username', 'action', 'token', 'tries', 'expires_at')
					->where('username', $username)
					->orderBy('id', 'desc')
					->first();

		return $otp;
	}
}