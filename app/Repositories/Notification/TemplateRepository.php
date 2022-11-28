<?php

namespace App\Repositories\Notification;

use App\Models\Notification\Template;

class TemplateRepository
{
	private $template;

	function __construct(Template $template)
	{
		$this->template = $template;
	}

	public function first($where)
	{
		$template = $this->template->where($where)->first();

		return $template;
	}

}
