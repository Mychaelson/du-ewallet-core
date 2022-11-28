<?php

namespace App\Repositories\Accounts;

use App\Models\Accounts\Users;

class ReferralRepository
{
	private $users;

	function __construct(Users $users)
	{
		$this->users = $users;
	}

  public function getUserbyNickname ($nickname) {
    $user = $this->getUser('nickname', $nickname)->orWhere('phone', $nickname)->first();
    if ($user) {
      return $user->toArray();
    }
    return $user;
  }

  public function updateReferral ($user, $cashtag){
    // $this->users->where('nickname', $user)->update(['referral_by' => $cashtag]);
    $this->getUser('nickname', $user)->limit(1)->update(['referral_by' => $cashtag]);
  }

  public function getReffererInformation ($referrerNickname){

    // change to search people that has been referred by the user that is logged in
    $userInfo = $this->users
              ->leftJoin('accounts.user_informations', 'users.id', '=', 'accounts.user_informations.user_id')
              ->where('referral_by', $referrerNickname)
              ->simplePaginate(10);

    return $userInfo;
  }

  public function getUser ( $coloumn, $data){
    return $this->users->where($coloumn, $data);
  }
}