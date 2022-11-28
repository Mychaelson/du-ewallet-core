<?php

namespace App\Http\Controllers\Ppob\Tv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Ppob\Tv\TvRepository;
use Illuminate\Support\Facades\Validator;

class TvController extends Controller
{
    protected $tv;

    public function __construct(TvRepository $tv)
    {
        $this->tv = $tv;
    }

    public function inquiry(Request $request)
    {
        $user_id = auth('api')->id();
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'customer_phone' => 'required',
            'customer_id' => 'required',
            'service_id' => 'required',
        ]);
        if ($validator->fails()) {
            return [
                'success' => false,
                'response_code' => 404,
                'message' => $validator->messages()->first(),
            ];
        }

        $data = $this->tv->inquiry($request, $user_id);

        return $data;
    }
}
