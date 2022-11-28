<?php

namespace App\Macros\Accounts;

use App\Repositories\RedisRepository;
use App\Repositories\Accounts\UsersRepository;
use App\Repositories\Accounts\RoleRepository;
use App\Repositories\Accounts\UserInformationsRepository;
use App\Repositories\Accounts\UserAddressRepository;
use App\Repositories\Accounts\UserJobsRepository;
use App\Repositories\Accounts\BankAccountsRepository;
use App\Repositories\Accounts\ProfileProgressRepository;
use App\Repositories\Accounts\ReasonsRepository;

class UserInformationMacro
{
	function __construct(
        private RedisRepository $redisRepository,
		private UsersRepository $usersRepository,
        private RoleRepository $roleRepository,
        private UserInformationsRepository $uiRepository,
        private UserAddressRepository $uaRepository,
        private UserJobsRepository $ujRepository,
        private BankAccountsRepository $bankAccountsRepository,
        private ProfileProgressRepository $profileProgressRepository, 
        private ReasonsRepository $reasonsRepository 
	)
	{}

	public function handle($data, $refresh = false)
	{
        $user = $data['user'];
        //should check from cache first
        if (!$refresh) {
            $information = $this->redisRepository->get("userInformation-".$user->id);
            $information = null;
            if(!is_null($information)){
                $data['response']['data'] = json_decode($information, true);
                return $data;
            }
        }

        if(isset($user->id)){
            //should get roles
            $roles = $this->roleRepository->getUserRoleByUserId($user->id);

            //should get information
            $userInfo = $this->uiRepository->getUserInformationByUserId($user->id);
            if (is_null($userInfo)){
                $this->uiRepository->create($user->id);
                $userInfo = $this->uiRepository->getUserInformationByUserId($user->id);
            }

            //should get reasons
            $reason = $this->reasonsRepository->getLatestReasonByUserId($user->id);
            $reason = is_null($reason) ? null : $reason->content;

            //should get main address
            $userAddress = $this->uaRepository->getUserMainAddressByUserId($user->id);

            //should get user jobs
            $userJobs = $this->ujRepository->getUserJobsByUserId($user->id);

            //should get user bank accounts
            $userBanks = $this->bankAccountsRepository->getUserBankAccountsByUserId($user->id);

            //should get profile progress
            $userProgress = $this->profileProgressRepository->getUserProfileProgressByUserId($user->id);
            if (is_null($userProgress)) {
                $this->profileProgressRepository->create($user->id);
                $userProgress = $this->profileProgressRepository->getUserProfileProgressByUserId($user->id);
            }

            //should calculate profile progress percentage
            $userProgressPercentage = round(
                                    ($userProgress->basic + $userProgress->profile + $userProgress->contact +
                                    $userProgress->document + $userProgress->address + 
                                    $userProgress->tax_information + $userProgress->recovery_security) / 7 * 100
                                    );

            //prepare the information
            $information = array(
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'phonecode' => (int) $user->phone_code,
                'whatsapp' => $user->whatsapp,
                'nickname' => $user->nickname,
                'gender' => $user->gender,
                'email' => $user->email,
                'email_verified' => (boolean) $user->email_verified,
                'avatar' => $user->avatar,
                'is_active' => (boolean) $userInfo->active,
                'is_set_main_device' => (is_null($user->main_device))? false : true,
                'main_device' => [
                    'device_id' => $user->main_device,
                    'device_name' => $user->main_device_name
                ],
                'user_type' => $user->user_type,
                'roles' => $roles,
                'information' => $userInfo->toArray(),
                'reason' => $reason,
                'job' => !is_null($userJobs) ? $userJobs->toArray() : $userJobs,
                'main_address' => !is_null($userAddress) ? $userAddress->toArray() : $userAddress,
                'last_address_changed' =>  $userAddress->updated_at ?? null,
                'bank_accounts' => !is_null($userBanks) ? $userBanks->toArray() : $userBanks,
                'progress' => $userProgress->toArray(),
                'profile_progress' => $userProgressPercentage,
                'referral_code' => $user->referral_code,
                'referral_by' => $user->referral_by,
                'referral_change_count' => $user->referral_change_count,
                'active_extra_password' => $user->password_extra,
                'is_active_password' => (boolean) $user->is_active_password,
                'location' => json_decode($user->location, true)
            );

            //should cache result for 1 hour
            $this->redisRepository->setNxPx("userInformation-".$user->id, json_encode($information), 3600000);
        }else{
            $information = null;
        }

           

        $data['response']['data'] = $information;
        return $data;
	}
}