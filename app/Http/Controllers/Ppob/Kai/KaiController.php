<?php

namespace App\Http\Controllers\Ppob\Kai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Ppob\Kai\KaiRepository;
use Illuminate\Support\Facades\Validator;

class KaiController extends Controller
{
    protected $kai;

    public function __construct(KaiRepository $kai)
    {
        $this->kai = $kai;
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

        $data = $this->kai->inquiry($request, $user_id);

        return $data;
    }
}
