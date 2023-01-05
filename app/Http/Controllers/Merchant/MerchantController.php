<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Accounts\UsersRepository;
use App\Repositories\Payment\BillRepository;

class MerchantController extends Controller
{
    protected $usersRepository;
    protected $billRepository;

    public function __construct(
        UsersRepository $usersRepository,
        BillRepository $billRepository
    )
    {
        $this->usersRepository = $usersRepository;
        $this->billRepository = $billRepository;
    }

    public function createBill(Request $request)
    {
        $response = init_transaction_data($request);
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'phone_user' => 'required',
            'invoice' => 'required',
            'description' => 'required',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = $validator->messages()->first();

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        $phone_number = $this->usersRepository->getUserByField('username', $request->input('phone_user'));        
        if (!isset($phone_number->id)) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = 'User not exist';

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        $merchant_token = $this->usersRepository->getUserByField('merchant_token', $request->input('token'));
        if (!isset($merchant_token->id)) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = 'Merchant not exist';

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        $data['invoice'] = $request->input('invoice');
        $data['description'] = $request->input('description');
        $data['customer'] = $phone_number->id;
        $data['merchant'] = $merchant_token->id;
        $data['amount'] = $request->input('amount');
        $data['bill_data'] = $request->input('bill_data');
        $data['namespace'] = 'App\Http\Controllers\Merchant\MerchantController';
        
        $createBill = $this->billRepository->createMerchantBill($data);
        return $createBill;
        /* 
        $user = $request->user();

         */
        
        // dd($user->id, $invoice_no, $period, $bill_member_id);

        /* $order_info = [
            'periode' => $periode,
            'bpjsMemberId' => $bill_member_id,
            'reff_no' => $invoice_no,
            'user_id' => $user->id,
            'product_code' => $product_code,
            'phone' => $request->customer_phone
        ];

        $inquiry = $this->bpjs->add_transaction($order_info);

        if (isset($inquiry) && !$inquiry['status']) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = $inquiry['message'] ?? $inquiry;

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        $response['response']['success'] = true;
        $response['response']['response_code'] = 200;
        $response['response']['message'] = $inquiry['message'];
        $response['response']['data'] = $inquiry['data'];

        return Response($response['response'])->header('Content-Type', 'application/json'); */
    }
}