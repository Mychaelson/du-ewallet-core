<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Repositories\Accounts\OTPRepository;
use App\Repositories\Accounts\UsersRepository;
use App\Repositories\Payment\PgaFundTransferRepository;
use App\Repositories\Wallet\WithdrawRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WithdrawController extends Controller
{
    public function __construct(
        private WithdrawRepository $withdrawRepository,
        private OTPRepository $otpRepository,
        private UsersRepository $usersRepository,
        private PgaFundTransferRepository $pgaFundTransferRepository,
    ) {
    }

    public function createWithdraw(Request $request, $walletId)
    {
        $data = init_transaction_data($request);

        try {
            $amount = $request->input('amount');

            $this->isWalletLocked($walletId);
            $this->checkPassword($request->password);

            if ($this->withdrawRepository->isNeedOTP($amount)) {
                $this->checkOTP('withdraw', $request->otp);
            }

            // check fee
            $withdrawFee = $this->withdrawRepository->getWithdrawFee();

            $this->validateWithdraw($walletId, $amount + $withdrawFee);

            $withdraw = $this->withdrawRepository->createWithdraw($walletId, (object) $request->all());

            $pga = $this->sendPgaRequest($withdraw);

            $withdraw = $this->withdrawRepository->updatePgInfo($withdraw->id, (object) [
                'pg_fee' => $pga?->fee ?? 0.00,
                'pg_informed' => $pga?->created_at ?? now(),
                'pg_confirmed' => null,
            ]);

            $this->withdrawRepository->updateBalance($walletId, $amount + $withdrawFee);

            $data['response']['message'] = '';
            $data['response']['data'] = $withdraw;

            return response()->json($data['response'], $data['response']['response_code']);
        } catch (\Throwable $th) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 422;
            $data['response']['message'] = $th->getMessage();
            $data['response']['data'] = [];

            return response()->json($data['response'], $data['response']['response_code']);
        }
    }

    public function createGroupWithdraw(Request $request, $walletId)
    {
        $data = init_transaction_data($request);

        try {
            $amount = $request->input('amount');

            $this->isWalletLocked($walletId);
            $this->checkPassword($request->password);

            if ($this->withdrawRepository->isNeedOTP($amount)) {
                $this->checkOTP('withdraw', $request->otp);
            }

            // check fee
            $withdrawFee = $this->withdrawRepository->getWithdrawFee();

            $this->validateGroupWithdraw($walletId, $amount + $withdrawFee, count($request->input('to')));

            $withdraw = $this->withdrawRepository->createGroupWithdraw($walletId, (object) $request->all());

            $this->withdrawRepository->updateBalance($walletId, $amount + $withdrawFee);

            $data['response']['message'] = '';
            $data['response']['data'] = $withdraw;

            return response()->json($data['response'], $data['response']['response_code']);
        } catch (\Throwable $th) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 422;
            $data['response']['message'] = $th->getMessage();
            $data['response']['data'] = [];

            return response()->json($data['response'], $data['response']['response_code']);
        }
    }

    public function createWithdrawPreview(Request $request, $walletId)
    {
        $data = init_transaction_data($request);

        // check fee
        $fee = $this->withdrawRepository->getWithdrawFee();

        $amount = $request->input('amount');
        $total = $amount + $fee;

        $data['response']['message'] = '';
        $data['response']['data'] = [
            'fee' => $fee,
            'total' => $total,
        ];

        return response()->json($data['response'], $data['response']['response_code']);
    }

    public function createGroupWithdrawPreview(Request $request, $walletId)
    {
        $data = init_transaction_data($request);

        $total = 0.00;
        $withdrawData = [];

        foreach ($request->input('to') as $value) {
            $fee = $this->withdrawRepository->getWithdrawFee();
            $amount = $value->amount;
            $subtotal = $amount + $fee;
            $total += $subtotal;
            $withdrawData[] = [
                'fee' => $fee,
                'total' => $subtotal,
            ];
        }

        $data['response']['message'] = '';
        $data['response']['data'] = [
            'total' => $total,
            'to' => $withdrawData,
        ];

        return response()->json($data['response'], $data['response']['response_code']);
    }

    public function fetchWithdrawFee(Request $request, $walletId)
    {
        $data = init_transaction_data($request);

        $fee = $this->withdrawRepository->getWithdrawFee();

        $data['response']['message'] = '';
        $data['response']['data'] = ['fee' => $fee];

        return response()->json($data['response'], $data['response']['response_code']);
    }

    public function fetchWithdrawByStatus(Request $request, $walletId)
    {
        $data = init_transaction_data($request);

        $withdraw = $this->withdrawRepository->getWithdrawByStatus($walletId, $request->status);

        $data['response']['message'] = '';
        $data['response']['data'] = $withdraw;

        return response()->json($data['response'], $data['response']['response_code']);
    }

    private function sendPgaRequest($data)
    {
        $pgaData = [
            'transaction_id' => $data->transaction_id,
            'unique_id' => $this->generateUniqueID($data->transaction_id),
            'description' => 'Withdraw',
            'amount' => $data->amount,
            'bank_code' => $data->bank_code,
            'bank_account_name' => $data->bank_account_name,
            'bank_account_number' => $data->bank_account_number,
        ];

        $requestPgaFundTrans = $this->pgaFundTransferRepository->store($pgaData);

        return $requestPgaFundTrans;
    }

    private function generateUniqueID($transId)
    {
        return 'WD-'.str($transId)->padLeft(5, '0')->value();
    }

    private function isWalletLocked($walletId)
    {
        // check status lock withdraw
        if ($this->withdrawRepository->isWithdrawLocked($walletId)) {
            throw new \Exception(trans('messages.wallet.withdraw.lock-wd'), Response::HTTP_FORBIDDEN);
        }
    }

    private function checkOTP($action, $otp)
    {
        // check otp
        $otp = validate_otp(auth()->user()->username, $action, $otp);

        if (is_null($otp)) {
            throw new \Exception(trans('messages.otp-invalid'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($otp->isExpired()) {
            throw new \Exception(trans('messages.otp-expired'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    private function checkPassword($password)
    {
        // check user pin
        if (! $this->usersRepository->validatePasswordHash($password, auth()->user()->password)) {
            throw new \Exception(trans('messages.password-invalid'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    private function validateWithdraw(int $walletId, int $amount)
    {
        // check balance
        $currentBalance = $this->withdrawRepository->getCurrentBalance($walletId);
        if ($currentBalance < $amount) {
            throw new \Exception(trans('messages.wallet.withdraw.insufficient-balance'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // check min withdraw
        $minWithdraw = $this->withdrawRepository->getMinWithdraw();
        if ($minWithdraw > $amount) {
            throw new \Exception(trans('messages.wallet.withdraw.min-transfer', ['min' => $minWithdraw]), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // check max withdraw
        $maxWithdraw = $this->withdrawRepository->getLimitWithdraw($walletId);
        if ($maxWithdraw < $amount) {
            throw new \Exception(trans('messages.wallet.withdraw.max-transfer', ['max' => $maxWithdraw]), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // check limit withdraw daily
        $currentTotal = $this->withdrawRepository->getCurrentTotalWithdrawDaily($walletId);
        $dailyTotal = $currentTotal + $amount;
        $currentLeft = $maxWithdraw - $dailyTotal;
        if ($dailyTotal > $maxWithdraw) {
            throw new \Exception(trans('messages.wallet.withdraw.daily-limit', ['limit' => $currentLeft]), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    private function validateGroupWithdraw(int $walletId, int $amount, int $totalMember = 1)
    {
        // check max withdraw group
        $maxWithdrawGroupMember = $this->withdrawRepository->getMaxWithdrawGroupMember($walletId);
        if ($maxWithdrawGroupMember < $totalMember) {
            throw new \Exception(trans('messages.wallet.withdraw.exceeded-group-member'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->validateWithdraw($walletId, $amount);
    }

    private function isMonthlyFreeAvailable($walletId)
    {
        return $this->withdrawRepository->getWithdrawMonthlyFree($walletId) > 0;
    }
}
