<?php

namespace App\Repositories\Ppob;

use App\Models\Ppob\Categories;

class DigitalCategoryRepository
{
	function __construct(
		private Categories $Categories
		)
	{
	}

	public function first($where)
	{
		$Categories = $this->Categories
		->join('ppob.digital_products', 'ppob.digital_products.category_id', '=', 'digital_categories.id')
		->where($where)->orderBy('digital_products.order')->limit(1)->get();

		return $Categories;
	}

	public function get()
	{
		$Categories = $this->Categories
		->where('parent_id', 0)->whereNotIn('slug', ['top-up-gsm'])
		->whereNotNull('group')->where('status', 1)->get();

		return $Categories;
	}

}
