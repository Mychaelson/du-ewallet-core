<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Repositories\Accounts\UsersRepository;
use App\Repositories\Wallet\WalletTransfersRepository;
use App\Resources\Wallet\WalletTransfer\Resource as ResultResource;
use App\Repositories\Wallet\WalletsRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class WalletTransfersController extends Controller
{
    public function __construct(
        private WalletTransfersRepository $walletTransfersRepository,
        private UsersRepository $usersRepository,
        private WalletsRepository $walletsRepository
    ) {
    }

    public function transferAction(Request $request)
    {
        $data = init_transaction_data($request, 'wallet.transferAction');
        
        if ($request->input('nickname')) {
            $transfer_destination = 'nickname';
            $user_to = $request->input('nickname');
            $user_field = 'nickname';
        } elseif ($request->input('phone')) {
            $transfer_destination = 'phone';
            $user_to = $request->input('phone');
            $user_field = 'phone';
        } else {
            $transfer_destination = 'user';
            $user_to = $request->input('user');
            $user_field = 'username';
        }

        // set validation
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'label' => 'required',
            $transfer_destination => 'required',
        ]);

        // response error validation
        if ($validator->fails()) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = Response::HTTP_BAD_REQUEST;
            $data['response']['message'] = $validator->errors()->first();
            $data['response']['data'] = $validator->errors();

            return response()->json($data['response'], $data['response']['response_code']);
        }

        //check user access
        if (! $this->isPremiumUser()) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = Response::HTTP_UNPROCESSABLE_ENTITY;
            $data['response']['message'] = trans('messages.wallet.transfer.premium-only');
            $data['response']['data'] = '';

            return response()->json($data['response'], $data['response']['response_code']);
        }

        //check status user destination
        if ($this->isDestinationNotFound($user_field, $user_to)) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = Response::HTTP_UNPROCESSABLE_ENTITY;
            $data['response']['message'] = trans('messages.wallet.transfer.destination-not-found');
            $data['response']['data'] = '';

            return response()->json($data['response'], $data['response']['response_code']);
        }

        //check self transfer
        if ($this->isSelfTransfer($user_field, $user_to)) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = Response::HTTP_UNPROCESSABLE_ENTITY;
            $data['response']['message'] = trans('messages.wallet.transfer.self-transfer');
            $data['response']['data'] = '';

            return response()->json($data['response'], $data['response']['response_code']);
        }

        $user_to_id = $this->usersRepository->getUserByField($user_field, $user_to)->id;
        $this->isWalletLocked($request, $user_to);
        $this->validateTransfer($request);
        $result = $this->walletTransfersRepository->store($request, $user_to_id);
        $data['response']['data'] = new ResultResource($result);

        return response()->json($data['response'], $data['response']['response_code']);
    }

    private function isPremiumUser()
    {
        return $this->usersRepository->getUserById(auth()->id())->user_type == 0;
    }

    private function isDestinationNotFound($field, $to)
    {
        return $this->usersRepository->getUserByField($field, $to) == null;
    }

    private function isSelfTransfer($field, $to)
    {
        return $this->usersRepository->getUserByField($field, $to)->id == auth()->id();
    }

    private function isWalletLocked($request, $user_to)
    {
        if ($this->walletTransfersRepository->checkLockTransfer($request) != 0) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = Response::HTTP_UNPROCESSABLE_ENTITY;
            $data['response']['message'] = trans('messages.wallet.transfer.lock-tf');
            $data['response']['data'] = '';
            
            return response()->json($data['response'], $data['response']['response_code']);
        } elseif ($this->walletTransfersRepository->checkLockOut($request) != 0) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = Response::HTTP_UNPROCESSABLE_ENTITY;
            $data['response']['message'] = trans('messages.wallet.transfer.lock-out');
            $data['response']['data'] = '';

            return response()->json($data['response'], $data['response']['response_code']);
        }elseif ($this->walletTransfersRepository->checkLockIn($user_to) != 0) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = Response::HTTP_UNPROCESSABLE_ENTITY;
            $data['response']['message'] = trans('messages.wallet.transfer.lock-in');
            $data['response']['data'] = '';

            return response()->json($data['response'], $data['response']['response_code']);
        }
    }

    private function validateTransfer($request)
    {
        $amount = $request->input('amount');

        //check label
        if ($this->walletTransfersRepository->checkLabel($request) == 0) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = Response::HTTP_UNPROCESSABLE_ENTITY;
            $data['response']['message'] = trans('messages.wallet.transfer.label-not-found');
            $data['response']['data'] = '';

            return response()->json($data['response'], $data['response']['response_code']);
        }

        // check balance
        $currentBalance = $this->walletsRepository->checkBalance();
        if ($currentBalance < $amount) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = Response::HTTP_UNPROCESSABLE_ENTITY;
            $data['response']['message'] = trans('messages.wallet.transfer.insufficient-balance');
            $data['response']['data'] = '';

            return response()->json($data['response'], $data['response']['response_code']);
        }

        // check min transfer
        $minTransfer = $this->walletTransfersRepository->checkMinTransfer($request)->value;
        if ($minTransfer > $amount) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = Response::HTTP_UNPROCESSABLE_ENTITY;
            $data['response']['message'] = trans('messages.wallet.transfer.min-transfer', ['min' => $minTransfer]);
            $data['response']['data'] = '';

            return response()->json($data['response'], $data['response']['response_code']);
        }

        // check max transfer
        $maxTransfer = $this->walletTransfersRepository->getLimitTransfer($request)->transfer_daily;
        if ($maxTransfer < $amount) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = Response::HTTP_UNPROCESSABLE_ENTITY;
            $data['response']['message'] = trans('messages.wallet.transfer.max-transfer', ['max' => $maxTransfer]);
            $data['response']['data'] = '';

            return response()->json($data['response'], $data['response']['response_code']);
        }

        // check limit transfer daily
        $currentTotal = $this->walletTransfersRepository->checkLimitTransferDaily($request);
        $dailyTotal = $currentTotal + $amount;
        $currentLeft = $maxTransfer - $dailyTotal;
        if ($dailyTotal > $maxTransfer) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = Response::HTTP_UNPROCESSABLE_ENTITY;
            $data['response']['message'] = trans('messages.wallet.transfer.daily-limit', ['limit' => $currentLeft]);
            $data['response']['data'] = '';

            return response()->json($data['response'], $data['response']['response_code']);
        }
    }
}
