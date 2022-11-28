<?php

namespace App\Http\Controllers\Accounts;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use App\Repositories\Accounts\ReferralRepository;
use GrahamCampbell\ResultType\Success;

class ReferralController extends Controller
{
    public function __construct
    (
        private ReferralRepository $referralRepository
    )
    {}

    public function register (Request $request)
    {
        $response = init_transaction_data($request);
        $user = $request->user();

        $this->validate($request, ['nickname' => 'required']);
        $cashtag = $request->nickname;

        // find the data of user that give the referral
        $upline = $this->referralRepository->getUserbyNickname($cashtag);

        // check if the user that give the referral exist
        if ($upline) {
            // check if the user referring to the user itself 
            if ($upline['nickname'] == $user->nickname || $upline['phone'] == $user->nickname) {
                $response['response']['success'] = false;
                $response['response']['response_code'] = 422;
                $response['response']['message'] = trans('messages.referral-code-invalid');
            } else {
                $this->referralRepository->updateReferral($user->nickname, $upline['nickname']);

                $response['response']['message'] = trans('messages.referral-success');
            }
        } else {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = trans('messages.user-not-found');
        }

        return Response($response['response'])->header('Content-Type', 'application/json');
    }

    public function getReferallList (Request $request)
    {
        $response = init_transaction_data($request);
        $user = $request->user();

        $referrerInformation = $this->referralRepository->getReffererInformation($user->nickname);

        $response['response']['data'] = $referrerInformation ?? [];
        $response['response']['message'] = trans('messages.referrer-found');

        return Response($response['response'])->header('Content-Type', 'application/json');
    }
}
