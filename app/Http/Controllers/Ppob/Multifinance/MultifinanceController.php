<?php

namespace App\Http\Controllers\Ppob\Multifinance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Ppob\Multifinance\MultifinanceRepository;
use Illuminate\Support\Facades\Validator;

class MultifinanceController extends Controller
{
    protected $multifinance;

    public function __construct(MultifinanceRepository $multifinance)
    {
        $this->multifinance = $multifinance;
    }

    public function inquiry(Request $request)
    {
        $user_id = auth('api')->id();
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'customer_phone' => 'required',
            'customer_id' => 'required',
            'service_id' => 'required'
        ]);
        if ($validator->fails()) {
            return [
                'success' => false,
                'response_code' => 404,
                'message' => $validator->messages()->first(),
            ];
        }

        $data = $this->multifinance->inquiry($request, $user_id);

        return $data;
    }
}
