<?php

namespace App\Http\Controllers\Connect;

use App\Http\Controllers\Controller;
use App\Repositories\Connect\AppsCredentialRepository;
use App\Repositories\Connect\AppsMerchantRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;

class UserConnectController extends Controller
{
    public function __construct(
        private AppsCredentialRepository $appsCredentialRepository,
        private AppsMerchantRepository $appsMerchantRepository
    ) {
    }

    public function getAppsConnected(Request $request)
    {
        //init data
        $data = init_transaction_data($request);

        $userId = auth()->id();
        $listApps = $this->appsCredentialRepository->getListByUserId($userId);

        if ($listApps->isEmpty()) {
            $data['response']['success'] = false;
            $data['response']['message'] = 'User are not connected to any apps';
            $data['response']['response_code'] = Response::HTTP_BAD_REQUEST;

            return response()->json($data['response'], $data['response']['response_code']);
        }

        foreach ($listApps as $app) {
            $merchant = $this->appsMerchantRepository->getByMerchantId($app->merchant_id);
            $app->merchant_name = $merchant?->name;
            $app->merchant_logo = $merchant?->logo;
        }

        $data['response']['data'] = $listApps;

        return response()->json($data['response'], $data['response']['response_code']);
    }

    public function revokeUserSavedPin(Request $request, int|string|null $merchantId)
    {
        //init data
        $data = init_transaction_data($request);

        $userId = auth()->id();
        $secret = [
            'user_id' => $userId,
            'merchant_id' => $merchantId,
        ];

        $revoked = $this->appsCredentialRepository->revoke($secret);

        if (!$revoked) {
            $data['response']['success'] = false;
            $data['response']['message'] = 'Unauthorized';
            $data['response']['response_code'] = Response::HTTP_UNAUTHORIZED;

            return response()->json($data['response'], $data['response']['response_code']);
        }

        auth()->logout();

        return response()->json($data['response'], Response::HTTP_NO_CONTENT);
    }
}
