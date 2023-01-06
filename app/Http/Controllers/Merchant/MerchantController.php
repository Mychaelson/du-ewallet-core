<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Accounts\UsersRepository;
use App\Repositories\Payment\BillRepository;
use Illuminate\Support\Facades\Auth;

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

        $data['invoice'] = $request->input('invoice');
        $data['description'] = $request->input('description');
        $data['customer'] = $phone_number->id;
        $data['merchant'] = Auth::id();;
        $data['amount'] = $request->input('amount');
        $data['bill_data'] = $request->input('bill_data');
        $data['namespace'] = 'App\Http\Controllers\Merchant\MerchantController';
        
        $inquiry = $this->billRepository->createMerchantBill($data);

        if (!isset($inquiry)) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = $inquiry['message'] ?? $inquiry;

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        $response['response']['success'] = true;
        $response['response']['response_code'] = 200;
        $response['response']['message'] = 'Ok';
        $response['response']['data'] = $inquiry;

        return Response($response['response'])->header('Content-Type', 'application/json');
    }

    public function payment ($invoice_no)
    {
        return [
            'status' => true,
            'message' => 'ok',
            'data' => [
                'invoice_no' => $invoice_no
            ]
        ];
    }
}