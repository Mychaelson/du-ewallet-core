<?php

namespace App\Http\Controllers\Ppob\Emoney;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Resources\Ppob\Data\DataResource as ResultResource;;

use App\Repositories\Ppob\Emoney\EmoneyRepository;
use Illuminate\Support\Facades\Validator;


class EmoneyController extends Controller
{
    protected $emoney;

    public function __construct(EmoneyRepository $emoney)
    {
        $this->emoney = $emoney;
    }

    public function addOrder(Request $request)
    {
        $user_id = auth('api')->id();
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'customer_phone' => 'required'
        ]);
        if ($validator->fails()) {
            return [
                'success' => false,
                'response_code' => 404,
                'message' => $validator->messages()->first(),
            ];
        }

        $data = $this->emoney->addOrder($request, $user_id);

        return $data;
    }
}
