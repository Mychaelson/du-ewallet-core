<?php

namespace App\Http\Controllers\Ppob\Subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Ppob\Subscription\SubscriptionRepository;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    protected $subscription;

    public function __construct(SubscriptionRepository $subscription)
    {
        $this->subscription = $subscription;
    }

    public function inquiry(Request $request)
    {
        $user_id = auth('api')->id();
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
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

        $data = $this->subscription->inquiry($request, $user_id);

        return $data;
    }
}
