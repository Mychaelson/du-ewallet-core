<?php

namespace App\Http\Controllers\Ppob\Coin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Ppob\Coin\CoinRepository;
use Illuminate\Support\Facades\Validator;

class CoinController extends Controller
{
    protected $coin;

    public function __construct(CoinRepository $coin)
    {
        $this->coin = $coin;
    }

    public function inquiry(Request $request)
    {
        $user_id = auth('api')->id();
        $this->validate($request, [
            'product_id' => 'required',
            'customer_phone' => 'required',
            'amount' => 'required',
            'service_id' => 'required',
        ]);

        $data = $this->coin->inquiry($request, $user_id);

        return $data;
    }
}
