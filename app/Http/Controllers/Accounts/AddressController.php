<?php

namespace App\Http\Controllers\Accounts;
use App\Http\Controllers\Controller;
use App\Models\Accounts\Subdistricts;
use App\Repositories\Accounts\UserAddressRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;



class AddressController extends Controller
{
    public function __construct
    (
        private UserAddressRepository $userAddressRepository
    )
    {}

    public function addAddress (Request $request){
      $response = init_transaction_data($request);
      $user = $request->user();

      // $validated = Validator::make($request->all(), [
      //   'address' => 'required',
      //   'province_id' => 'required',
      //   'city_id' => 'required',
      //   'subdistrict_id' => 'required',
      //   'village_id' => 'required'
      // ]);

      // if ($validated->fails()) {
      //   $response['response']['success'] = false;
      //   $response['response']['response_code'] = 422;
      //   $response['response']['message'] = 'error';
      //   $response['response']['data'] = $validated->errors();

      //   return Response($response['response'])->header('Content-Type', 'application/json');
      // };

      $userMainAddress = $this->userAddressRepository->getUserMainAddressByUserId($user->id);

      $addressInfo = $request->only(
        'name',
        'phone',
        'is_main',
        'address',
        'province_id',
        'city_id',
        'subdistrict_id',
        'village_id',
        'postal_code'
      );

      $additionalInfo = [
        'user_id' => $user->id,
        'created_at' => now(),
        'updated_at' => now(),
      ];

      $addressInfo = array_merge($addressInfo, $additionalInfo);

      if (!array_key_exists('is_main', $addressInfo) || !$userMainAddress) {
        $addressInfo['is_main'] = 1;
      }
        
      if ($addressInfo['is_main']) {
        $this->userAddressRepository->updateIsMainAddress($user->id);
      }

      $this->userAddressRepository->addAddress($addressInfo);

      $userAddress = $this->userAddressRepository->getUserMainAddressByUserId($user->id);

      $response['response']['data'] = $userAddress;
      $response['response']['message'] = trans('messages.address-added');

      return Response($response['response'])->header('Content-Type', 'application/json');
    }
}
