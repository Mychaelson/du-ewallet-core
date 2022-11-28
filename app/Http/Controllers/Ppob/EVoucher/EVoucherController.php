<?php

namespace App\Http\Controllers\Ppob\EVoucher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Ppob\EVoucher\EVoucherRepository;
use Illuminate\Support\Facades\Validator;

class EVoucherController extends Controller
{
    protected $evoucher;

    public function __construct(EVoucherRepository $evoucher)
    {
        $this->evoucher = $evoucher;
    }

    public function inquiry(Request $request)
    {
        $user_id = auth('api')->id();
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'customer_phone' => 'required',
            'service_id' => 'required',
        ]);
        if ($validator->fails()) {
            return [
                'success' => false,
                'response_code' => 404,
                'message' => $validator->messages()->first(),
            ];
        }

        $data = $this->evoucher->inquiry($request, $user_id);

        return $data;
    }
}
