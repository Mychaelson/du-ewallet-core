<?php

namespace App\Http\Controllers\Ppob\Telkom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Ppob\Telkom\TelkomRepository;
use Illuminate\Support\Facades\Validator;

class TelkomController extends Controller
{
    protected $Telkom;

    public function __construct(TelkomRepository $Telkom)
    {
        $this->Telkom = $Telkom;
    }

    public function inquiry(Request $request)
    {
        $user_id = auth('api')->id();
        $validator = Validator::make($request->all(), [
            'code' => 'required',
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

        $data = $this->Telkom->inquiry($request, $user_id);

        return $data;
    }
}
