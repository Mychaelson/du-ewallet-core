<?php

namespace App\Http\Controllers\Ppob\Gas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Ppob\Gas\GasRepository;
use Illuminate\Support\Facades\Validator;

class GasController extends Controller
{
    protected $Gas;

    public function __construct(GasRepository $Gas)
    {
        $this->Gas = $Gas;
    }

    public function inquiry(Request $request)
    {
        $user_id = auth('api')->id();
        $validator = Validator::make($request->all(), [
            'code' => 'required',
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

        $data = $this->Gas->inquiry($request, $user_id);

        return $data;
    }
}
