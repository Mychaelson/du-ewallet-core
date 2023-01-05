<?php

namespace App\Repositories\Notification;

use App\Models\Notification\Notifications;
use App\Repositories\Accounts\UserInformationsRepository;

class NotificationRepository
{
	private $notifications, $userInformationRepository;

	function __construct(Notifications $notifications, UserInformationsRepository $userInformationRepository)
	{
		$this->notifications = $notifications;
		$this->userInformationRepository = $userInformationRepository;
	}

	public function getGroupByCategory($where, $selectRaw, $q = null)
	{
		$notifications = $this->notifications->where($where);

		if ($q)
			$notifications = $notifications->where('category', 'ILIKE', "%{$q}%");

		$query = "sum(case when read_at  = null then 1 else 0 end) as unread, category";

		$notifications = $notifications->selectRaw($query)->groupBy('category')->get()->pluck('unread', 'category');

		return $notifications;
	}

	public function getPaginate($where, $likeData = null, $perpage = 10)
	{
		$notifications = $this->notifications->where($where);

		if ($likeData)
			$notifications = $notifications->where('data', 'ILIKE', "%{$likeData}%");

		$notifications = $notifications->orderBy('created_at', 'asc')->paginate($perpage);

		return $notifications;
	}

	public function getLast($where)
	{
		$notifications = $this->notifications->where($where)->latest()->first();

		return $notifications;
	}

	public function read($where, $update)
	{
		$this->notifications->where($where)->update($update);

		$notifications = $this->notifications->where($where)->get();

		return $notifications;
	}

	public function sendWebNotification($info)
    {
        $url = env('FCM_URL');
        $FcmToken = $this->userInformationRepository->getUserDeviceInfo($info['user_id']);
          
				foreach ($FcmToken as $value) {
					$serverKey = env('FCM_SERVERKEY');
		
					$data = [
							"registration_ids" => [$value],
							"notification" => [
									"title" => $info['title'],
									"body" => $info['body'],
							]
					];
					$encodedData = json_encode($data);
			
					$headers = [
							'Authorization:key=' . $serverKey,
							'Content-Type: application/json',
					];
			
					$ch = curl_init();
				
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
					// Disabling SSL Certificate support temporarly
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
					curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
					// Execute post
					$result = curl_exec($ch);
					if ($result === FALSE) {
							die('Curl failed: ' . curl_error($ch));
					}        
					// Close connection
					curl_close($ch);
				}
        // FCM response
        return $result;   
    }

		public function createNotification ($notifInfo){ 
			$data = [
				"type" => $notifInfo['type'],
				"notifiable_id" => $notifInfo['user_id'],
				'notifiable_type' => $notifInfo['notifiable_type'],
				'data' => $notifInfo['data'],
				'category' => $notifInfo['category'],
				'icon' => $notifInfo['icon'],
				'merchant_id' => $notifInfo['merchant_id']
			];

			$res = $this->notifications->insert($data);

			return $res;
		}
}
