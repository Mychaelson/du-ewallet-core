<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\SecurityQuestions;

class SecurityQuestionsRepository
{
	private $securityQuestions;

	function __construct(SecurityQuestions $securityQuestions)
	{
		$this->securityQuestions = $securityQuestions;
	}

	public function create($data)
	{
		$this->securityQuestions->insert($data);
	}

	public function update($id, $data)
	{
		$this->securityQuestions->where('id', $id)->update($data);
	}

	public function getSecurityQuestionsByUserId($userId)
	{
		$questions = $this->securityQuestions->select(
						'id',
						'user_id',
						'question',
						'answer',
						'order',
					)
					->where('user_id', $userId)
					->get();

		return $questions;
	}

	public function getSecurityQuestionById($Id)
	{
		$question = $this->securityQuestions->select(
						'id',
						'user_id',
						'question',
						'answer',
						'order',
					)
					->where('id', $Id)
					->first();

		return $question;
	}

	public function getSecurityQuestionsByUserIdAndOrder($userId, $order)
	{
		$questions = $this->securityQuestions->select(
						'id',
						'user_id',
						'question',
						'answer',
						'order',
					)
					->where('user_id', $userId)
					->where('order', $order)
					->first();

		return $questions;
	}
}