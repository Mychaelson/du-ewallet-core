<?php

namespace App\Repositories\Ppob;

use App\Models\Ppob\DigitalCategories;

class DigitalCategoryRepository
{
	function __construct(
		private DigitalCategories $digitalCategories
		)
	{
	}

	public function first($where)
	{
		$digitalCategories = $this->digitalCategories
		->join('ppob.digital_products', 'ppob.digital_products.category_id', '=', 'digital_categories.id')
		->where($where)->orderBy('digital_products.order')->limit(1)->get();

		return $digitalCategories;
	}

	public function get()
	{
		$digitalCategories = $this->digitalCategories
		->where('parent_id', 0)->whereNotIn('slug', ['top-up-gsm'])
		->whereNotNull('group')->where('status', 1)->get();

		return $digitalCategories;
	}

}
