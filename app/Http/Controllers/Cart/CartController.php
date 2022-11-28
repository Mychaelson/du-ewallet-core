<?php

namespace App\Http\Controllers\Cart;

use Illuminate\Http\Request;
use App\Models\CartUser;

class CartController extends Controller
{
  public function response($error, $data = [], $message = null)
  {
    $response = [
      'error'   => $error,
      'message' => $message,
      'data'    => $data,
      'total' => $error == 0 ? count($data) : 0];

    $headers['Connection'] = 'close';
    $headers['Content-Type'] = 'application/json';
    return response()->json($response, 200, $headers);
  }

  public function __construct(Request $request)
  {
    // if (!$request->user())
    //   return $this->response(401);
  }

  private $request;

  public function getOptionalRequest($value, $default = null)
  {
    if (isset($this->request->$value))
      return $request->$value;
    return $default;
  }

  public function getCarts(Request $request)
  {
    $this->request = $request;
    $rpp = (int)$this->getOptionalRequest('rpp', 12);
    $page = (int)$this->getOptionalRequest('page', 1);
    $page -= 1;

    $status = $this->getOptionalRequest('status');
    if($status && strstr($status,','))
      $status = explode(',', $status);

    $cond = [
      'user' => 26,
      'deleted' => false,
      'status' => $status ? $status : [1,2]];

    if($status == '*')
      unset($cond['status']);

    $type = $this->getOptionalRequest('type');
    if($type)
      $cond['type'] = $type;

    //WARNING get user

    if(isset($cond['status'])){
      $cond['cart.status'] = $cond['status'];
      unset($cond['status']);
    }

    $cond['cart.deleted'] = $cond['deleted'];
    unset($cond['deleted']);

    $cartUser = CartUser::where($cond)
    ->skip($page)->take($rpp)->orderBy('created', 'desc')->get();

    return $this->response(0, $ucarts);
  }

  public function gCartWallet($id)
  {
    // $scopes = json_decode($this->user->token->scopes);
    // if(!in_array('cart_single_readonly', $scopes)){
    //
      // $cart_user = CUser::where([
      //   'cart' => $id,
      //   'user' => 26
      // ])->get();
      // if(!$cart_user)
      //   return $this->response(404, 0);
    // }

    $cart = Cart::where(['id'=>$id, 'deleted'=>false])->get();
    if(!$cart)
      return $this->response(404, 0);

    return $this->response(0, $cart);
  }

  public function deleteCart($id)
  {
    $cartUser = CUser::where([
      'cart' => $id,
      'user' => 26])->get();
    if(!$cartUser)
      return $this->response(404, 0);

    $cart = Cart::where(['id'=>$id, 'status'=>1])->get();
    if(!$cart)
      return $this->response(404, 0);

    Card::where(['id'=>$id])->update(['status'=>0, 'deleted'=>true]);
    CUser::where(['cart'=>$id])->update(['status'=>0]);

    return $this->response(0, [], 'Success');
  }

  public function createAction(Request $request)
  {
		$cart = [
			'type'			=> $request->type,
			'note'			=> $request->note,
			'description' 	=> $request->description,
			'items' 		=> 0,
			'subtotal' 		=> 0,
			'fee' 			=> 0,
			'tax' 			=> 0,
			'total' 		=> 0,
			'total_text'	=> 'Nol'
		];

		$prods 		     = [];
		$qty_by_prod   = [];
		$note_by_prod  = [];

		$dynamic_price = [];
		$dynamic_ids   = [];
		$dynamic_info  = [];
		$dynamic_metas = [];

		$products = $request->items;

		if($products){
			$product_ids = [];
			foreach($products as $index => $prod){
				if(!isset($prod->product))
          return $this->response(422, ['items.product' => 'This field is required']);
				if(!isset($prod->quantity))
          return $this->response(422, ['items.quantityZ' => 'This field is required']);

				$product_ids[] = $prod->product;

				$dynamic_ids[$prod->product] 	= $prod->order_id ?? null;
				$qty_by_prod[$prod->product] 	= $prod->quantity;
				$dynamic_metas[$prod->product] 	= $prod->meta ?? null;
				$note_by_prod[$prod->product]	= $prod->note ?? null;

				if($dynamic_metas[$prod->product])
					$dynamic_metas[$prod->product] = json_encode($dynamic_metas[$prod->product]);

				// make sure the order id is not yet on our cart
				if($dynamic_ids[$prod->product]){
					$exists_order_id = CItem::where([
						'product' => $prod->product,
						'dynamic_id' => $dynamic_ids[$prod->product]])->first();

					if($exists_order_id){
						// make sure the cart is already cancelled
						$exists_cart = Cart::where(['id'=>$exists_order_id->cart])->first();

						if($exists_cart->status != 0){
              return $this->response(422, [
								'cart' => (int)$exists_order_id->cart,
								'order_id' => $dynamic_ids[$prod->product]], 'order id already used');
						}
					}
				}

				$cart['items']+= $prod->quantity;
			}

			if(!$product_ids)
				$this->resp(['items'=>lang('invalid_product_data')], 422);

			$prods = [];
			foreach($product_ids as $id){
				$prod = MApi::getProduct($id);
				if(!$prod)
					$this->resp(['items'=>lang('product_not_found')], 422);
				$prods[] = $prod;
				if(!$cart['description']){
					$cart['description'] = $prod->merchant->name;
                    if($prod->merchant->address->village && $prod->merchant->address->village->name)
                        $cart['description'].= ', ' . $prod->merchant->address->village->name;
                }
                $cart['image'] = $prod->merchant->logo;
			}

			$prod_exists = [];
			$used_expiration = strtotime('+7 days');
			$today = time();
			foreach($prods as $index => $prod){
				$qty = $qty_by_prod[$prod->id];
				$prod_exists[] = $prod->id;
				$dynamic_price[$prod->id] = $price = $prod->price;

				if($prod->endpoint_details && !isset($dynamic_ids[$prod->id]))
					return $this->resp(['order_id' => lang('this_field_is_required')], 422);

				// fetch dynamic products
				if($prod->endpoint_details && isset($dynamic_ids[$prod->id])){
					$dyn_id = $dynamic_ids[$prod->id];

					$dyn_details = $this->dynProduct->getDetails($prod, $dyn_id);

					if(!$dyn_details)
						return $this->resp(['order_id' => lang('invalid_order_id_or_not_found')], 422);

					if(isset($dyn_details->price)){
						$price = $dyn_details->price;
						$dynamic_price[$prod->id] = $price;
					}
					$dynamic_info[$prod->id] = json_encode($dyn_details);

					if(isset($dyn_details->expired)){
						$prod_expiration = strtotime($dyn_details->expired . ' UTC');
						if($today > $prod_expiration)
							return $this->resp(['order_id' => lang('order_expired_date_is_invalid')], 422);
						if($prod_expiration < $used_expiration)
							$used_expiration = $prod_expiration;
					}
				}

				$cart['subtotal']+= ($price * $qty);
				$cart['fee']+= ($prod->admin_fee * $qty);
				$cart['tax']+= ($prod->tax * $qty);
			}

			$cart['expired'] = gmdate('Y-m-d H:i:s', $used_expiration);

			$cart['total'] = $cart['subtotal'] + $cart['tax'] + $cart['fee'];
			$cart['total_text'] = Etc::numberToWord($cart['total']);
		}

		$cart_id = Cart::create($cart);
		$cart = Cart::get(['id'=>$cart_id], false);
		if(!$cart)
			return $this->resp('DB Error', 503);

		// insert each of the product to cart_item
		if($prods && $cart->subtotal){
			$prod_items = [];
			foreach($prods as $prod){
				$qty 		= $qty_by_prod[$prod->id];
				$price 		= $dynamic_price[$prod->id];
				$tax 		= (int)$prod->tax * $qty;
				$fee 		= (int)$prod->admin_fee * $qty;
				$subtotal 	= (int)$price * $qty;
				$total 		= $subtotal + $fee + $tax;
				$note   	= $note_by_prod[$prod->id];

				$prod_items[] = [
					'cart' 				=> $cart->id,
					'merchant' 			=> $prod->merchant_id,
					'product' 			=> $prod->id,
					'product_snap'		=> json_encode($prod),
					'price' 			=> $price,
					'quantity' 			=> $qty,
					'total' 			=> $total,
					'dynamic_id' 		=> $dynamic_ids[$prod->id]   ?? null,
					'metas'				=> $dynamic_metas[$prod->id] ?? null,
					'dynamic_info'		=> $dynamic_info[$prod->id]  ?? null,
					'endpoint_success' 	=> $prod->endpoint_success,
					'endpoint_checks'	=> $prod->endpoint_checks ?? null,
					'fee'				=> $fee,
					'tax'				=> $tax,
					'note'				=> $note,
					'subtotal'			=> $subtotal
				];
			}

			CItem::createMany($prod_items);
		}

		CUser::create([
			'cart' => $cart->id,
			'user' => 26
		]);

		$cart = \Formatter::format('cart-rsp', $cart, false, false);

		$this->resp($cart);
	}

  public function billAction()
  {
  		// HOLDER
  		return $this->resp(null, 501);

  		if(!$this->user->isLogin())
  			return $this->resp(null, 401);

  		$id = $this->param->id;
  		$cart_user = CUser::get([
  			'cart' => $id,
  			'user' => 26
  		], false);
  		if(!$cart_user)
  			return $this->show404();

  		$cart_items = CItem::get(['cart'=>$id]);
  		if(!$cart_items)
  			return $this->resp([], null, ['total'=>0]);

  		$cart_items = \Formatter::formatMany('cart-item-rsp', $cart_items, 'id', [
  			'merchant', 'product'
  		]);

  		foreach($cart_items as $ind => $item){
  			$item->bill = (object)[
  				'unpicked' => $item->quantity->value,
  				'splits' => []
  			];

  			$cart_items[$ind] = $item;
  		}

  		$cart_users = CUser::get(['cart'=>$id]);
  		if(!$cart_users){
  			return $this->resp(array_values($cart_items), null, [
  				'total' => count($cart_items)
  			]);
  		}

  		$cart_users_id = array_column($cart_users, 'id');
  		$cart_users = \Formatter::formatMany('cart-user-rsp', $cart_users, 'id', ['user','cart', 'invoice', 'recipt']);

  		$cart_user_items = CUItem::get(['cart_user'=>$cart_users_id]);
  		if(!$cart_user_items){
  			$this->resp(array_values($cart_items), null, [
  				'total' => count($cart_items)
  			]);
  		}

  		foreach($cart_user_items as $item){
  			$cart_item = $cart_items[$item->cart_item];
  			$cart_item->bill->splits[] = (object)[
  				'quantity' => (int)$item->quantity,
  				'user' => $cart_users[$item->cart_user]
  			];
  			$cart_items[$item->cart_item] = $cart_item;
  		}

  		foreach($cart_items as $ind => $item){
  			foreach($item->bill->splits as $bill)
  				$item->bill->unpicked-= $bill->quantity;
  			$cart_items[$ind] = $item;
  		}

  		$this->resp(array_values($cart_items), null, [
  			'total' => count($cart_items)
  		]);
  	}



	public function expirationAction()
    {
		$cond = 'status = 1 AND expired < NOW()';
		$len = Cart::count($cond);
		if($len)
			Cart::set(['status'=>0], $cond);
		$this->resp('success', null, ['total'=>$len]);
	}



	public function invoiceAction()
    {
		if(!$this->user->isLogin())
			return $this->resp(null, 401);

		$id = $this->param->id;

		$cart_user = CUser::get([ 'cart' => $id, 'user' => 26 ], false);
		if(!$cart_user)
			return $this->show404();

		$invoices = Invoice::get(['cart'=>$id], true);

		if(!$invoices)
			return $this->resp([], null, ['total'=>0]);

		$invoices = \Formatter::formatMany('invoice-rsp', $invoices, false, [
			'cart', 'cart_user', 'recipt'
		]);

		$data = ['user'=>[], 'merchant'=>[]];
		foreach($invoices as $invoice){
			if($invoice->user)
				$data['user'][] = $invoice;
			else
				$data['merchant'][] = $invoice;
		}

		$this->resp($data, null, ['total'=>count($invoices)]);
	}

	public function latestAction()
    {
		if(!$this->user->isLogin())
			return $this->resp(null, 401);

		$cart_user = CUser::getX(['user' => 26, 'deleted'=>false], false, false, 'id DESC');

		if(!$cart_user)
			return $this->resp(null, 404);

		$cart = Cart::get(['id'=>$cart_user->cart], false);

		$cart = \Formatter::format('cart-rsp', $cart, false);

		$this->resp($cart);
	}

	public function reciptAction()
    {
		if(!$this->user->isLogin())
			return $this->resp(null, 401);

		$id = $this->param->id;

		$cart_user = CUser::get([ 'cart' => $id, 'user' => 26 ], false);
		if(!$cart_user)
			return $this->show404();

		$recipts = Recipt::get(['cart'=>$id], true);

		if(!$recipts)
			return $this->resp([], null, ['total'=>0]);

		$recipts = \Formatter::formatMany('recipt-rsp', $recipts, false, [
			'cart', 'cart_user', 'invoice'
		]);

		$data = ['user'=>[], 'merchant'=>[]];
		foreach($recipts as $recipt){
			if($recipt->user)
				$data['user'][] = $recipt;
			else
				$data['merchant'][] = $recipt;
		}

		$this->resp($data, null, ['total'=>count($recipts)]);
	}



	public function updateAction()
    {
		if(!$this->user->isLogin())
			return $this->resp(null, 401);

		$id = $this->param->id;
		$cart_user = CUser::get([
			'cart' => $id,
			'user' => 26
		], false);
		if(!$cart_user)
			return $this->show404();

		$cart = Cart::get(['id'=>$id, 'status'=>1], false);
		if(!$cart)
			return $this->show404();

		$note = $this->req->getBody('note');
		$desc = $this->req->getBody('description');
		$type = $this->req->getBody('type');

		$cart_set = [];
		if(!is_null($note))
			$cart_set['note'] = $cart->note = $note;
		if(!is_null($desc))
			$cart_set['description'] = $cart->description = $desc;
		if(!is_null($type))
			$cart_set['type'] = $cart->type = $type;

		if($cart_set)
			Cart::set($cart_set, ['id'=>$cart->id]);

		$cart = \Formatter::format('cart-rsp', $cart, false);

		$this->resp($cart);
	}
}
