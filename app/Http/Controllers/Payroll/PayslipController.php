<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Repositories\Accounts\OTPRepository;
use App\Repositories\Payroll\EmployeeRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class PayslipController extends Controller
{
    public function __construct(
        private OTPRepository $otpRepository,
        private EmployeeRepository $employeeRepository
    ) {
    }

    public function sendOTP(Request $request)
    {
        //init data
        $data = init_transaction_data($request);

        // Dispatch Notification
        $notif = generate_otp(auth()->user()->username, 'payroll-done');

        if (! $notif) {
            $data['response']['success'] = false;
            $data['response']['message'] = 'There is a problem with the otp, wait to re-send';
            $data['response']['response_code'] = Response::HTTP_UNPROCESSABLE_ENTITY;

            return response()->json($data['response'], $data['response']['response_code']);
        }

        $data['response']['message'] = trans('messages.sms-sent', ['phone' => auth()->user()->phone]);

        return response()->json($data['response'], $data['response']['response_code']);
    }

    public function getPinForPdf(Request $request)
    {
        //init data
        $data = init_transaction_data($request);

        $employee = $this->employeeRepository->getEmployeeByUserId(auth()->id());

        if (empty($employee?->pass_pdf)) {
            $data['response']['success'] = false;
            $data['response']['message'] = 'Setup Payslip PIN First';
            $data['response']['response_code'] = Response::HTTP_UNPROCESSABLE_ENTITY;

            return response()->json($data['response'], $data['response']['response_code']);
        }

        $data['response']['data'] = [
            'pin_pdf' => (string) base64_decode($employee->pass_pdf),
            'latest_update' => $employee?->updated_at ?: $employee->created_at,
        ];

        return response()->json($data['response'], $data['response']['response_code']);
    }

    public function getLatestPin(Request $request)
    {
        //init data
        $data = init_transaction_data($request);

        $employee = $this->employeeRepository->getEmployeeByUserId(auth()->id());

        if (empty($employee?->pass_pdf)) {
            $data['response']['success'] = false;
            $data['response']['message'] = 'Setup Payslip PIN First';
            $data['response']['response_code'] = Response::HTTP_UNPROCESSABLE_ENTITY;

            return response()->json($data['response'], $data['response']['response_code']);
        }

        $data['response']['data'] = [
            'latest_update' => $employee?->updated_at ?: $employee->created_at,
        ];

        return response()->json($data['response'], $data['response']['response_code']);
    }

    public function setPin(Request $request)
    {
        //init data
        $data = init_transaction_data($request);

        try {
            $validator = Validator::make([
                'password' => 'required|digits:6|numeric',
            ], [
                'digits' => 'Payslip PIN must 6 Digits',
                'numeric' => 'Digit only, not accepting alphabet or symbol',
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $password = $request->input('password');

            $this->employeeRepository->updatePdfPassword(auth()->id(), $password);

            $data['response']['message'] = 'Successful Create Payslip PIN';

            return response()->json($data['response'], $data['response']['response_code']);
        } catch (\Throwable $th) {
            $data['response']['success'] = false;
            $data['response']['message'] = $th->getMessage();
            $data['response']['response_code'] = $th->getCode();

            return response()->json($data['response'], $data['response']['response_code']);
        }
    }

    public function authOTP(Request $request)
    {
        //init data
        $data = init_transaction_data($request);

        try {
            $validator = Validator::make([
                'otp' => 'required|numeric|digits:6',
            ], [
                'digits' => 'OTP password must 6 Digits',
                'numeric' => 'Digit only, not accepting alphabet or symbol',
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $token = $request->input('otp');

            $otp = $this->otpRepository->validate(auth()->user()->username, 'payroll-done', $token);

            if (is_null($otp)) {
                throw new \Exception(trans('messages.otp-invalid'), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if ($otp->isExpired()) {
                throw new \Exception(trans('messages.otp-expired'), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $this->otpRepository->delete(auth()->id());

            $data['response']['message'] = 'OTP is valid';

            return response()->json($data['response'], $data['response']['response_code']);
        } catch (\Throwable $th) {
            $data['response']['success'] = false;
            $data['response']['message'] = $th->getMessage();
            $data['response']['response_code'] = $th->getCode();

            return response()->json($data['response'], $data['response']['response_code']);
        }
    }
}
