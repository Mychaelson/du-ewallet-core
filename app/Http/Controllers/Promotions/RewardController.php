<?php

namespace App\Http\Controllers\Promotions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Promotions\Cashback;
use App\Models\Promotions\Coupon;

use App\Resources\Promotions\Catalogue\Reward\Collection as RewardCollection;

class RewardController extends Controller
{
    

	function __construct()
	{
	}

    public function cashback(Request $request)
    {
        $cashback = Cashback::select([\DB::raw("sum(amount) as amount")])
                            ->whereNull('cashout_at')
                            ->whereIn('status', [0,2])
                            ->where('redeemed_by', $request->user()->id)
                            ->orWhere('transaction_id', 'referred')
                            ->first();

        $data = [
            'amount' => (int) $cashback->amount
        ];

        $response = [
            "success"       => true,
            "response_code" => 200,
            "data"       => $data,
        ];

        return response()->json($response,200);
        
    }

    // product exchange / redeemed
    public function rewards(Request $request)
    {
        $rewards = Coupon::whereNull('released_at')->where('user_id', $request->user()->id)->orderBy('id', 'desc')->paginate();

        return new RewardCollection($rewards);
    }

}
