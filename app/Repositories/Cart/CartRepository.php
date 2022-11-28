<?php

namespace App\Repositories\Cart;

use App\Models\Cart\CartUser;

class CartRepository
{
	private $cartUser;

	function __construct(CartUser $cartUser)
	{
		$this->cartUser = $cartUser;
	}
}
