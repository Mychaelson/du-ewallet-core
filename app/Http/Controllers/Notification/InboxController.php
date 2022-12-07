<?php

namespace App\Http\Controllers\Notification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Notification\NotificationRepository;
use App\Macros\Notifications\SendMailMacro;

class InboxController extends Controller
{
  private $userId;
  private $notificationRepository;


  function __construct(
    NotificationRepository $notificationRepository,
    private SendMailMacro $sendMail
  ) {
    $this->userId = auth('api')->id();
    $this->notificationRepository = $notificationRepository;
  }

  public function inbox(Request $request)
  {
    $where = ['notifiable_id' => $this->userId];
    $selectRaw = 'SUM(IF(read_at is NULL, 1,0)) as unread, category';

    $notifications = $this->notificationRepository->getGroupByCategory($where, $selectRaw, $request->q);

    if (isset($request->category)) {
      $where = [
        'category' => $request->category,
        'notifiable_id' => $this->userId
      ];
      $perpage = $request->per_page;

      $notifications = $this->notificationRepository->getPaginate($where, $request->q, $perpage);
      return $this->response($notifications);
    }

    $groups = [
      [
        'category' => 'Customer Care',
        'icon' => asset('white_logo.png'),
        'last_content' => 'help',
        'last_updated' => date('Y-m-d H:i:s'),
        'last_activity' => 'last_activity',
        'activity' => 'ticket_support',
        'created_at' => date('Y-m-d H:i:s'),
        'unread_count' => 0
      ],
      [
        'category' => 'Transaction',
        'icon' => 'http://cdn-apps.nusapay.co.id/nhWJH60j0DgvNsKXuaRDCYjkxKPLF2.png',
        'last_content' => 'New Transaction',
        'last_updated' => date('Y-m-d H:i:s'),
        'last_activity' => 'transaction_activity',
        'activity' => 'transaction_invoice',
        'created_at' => date('Y-m-d H:i:s'),
        'unread_count' => (int) 0
      ],
    ];

    foreach ($notifications as $cat => $unread) {
      $where = [
        'category' => $request->category,
        'notifiable_id' => $this->userId
      ];

      $lastNotif = $this->notificationRepository->getLast($where);
      if ($lastNotif) {
        $data = (array)json_decode($lastNotif->data);
        $groups[] = [
          'category' => $cat,
          'icon' => $lastNotif->icon ?? 'http://cdn-apps.nusapay.co.id/I3OXSmlYllpI9pDMujJdCi6mEtzlCi.png',
          'last_content' => $data['content'] ?? '',
          'last_updated' => $lastNotif->created_at,
          'last_activity' => $data['activity'] ?? '',
          'activity' => 'list_inbox',
          'created_at' => $lastNotif->created_at,
          'unread_count' => (int) $unread,
        ];
      }
    }

    $groups = collect($groups)->sortByDesc('created_at')->values();
    return $this->response($groups);
  }

  public function inboxCategory($category, Request $request)
  {
    $where = [
      'category' => $request->category,
      'notifiable_id' => $this->userId
    ];
    $perpage = $request->per_page; // optional

    $notifications = $this->notificationRepository->getPaginate($where, $perpage);

    return $this->response($notifications);
  }

  public function read($id)
  {
    $where = ['id' => $id];
    $update = ['read_at' => date('Y-m-d H:i:s')];
    $notifications = $this->notificationRepository->read($where, $update);

    return $this->response($notifications);
  }

  public function response($data)
  {
    return response()->json([
      'success' => true,
      'response_code' => 200,
      'data' => $data
    ], 200);
  }

  public function sendEmail(Request $request)
  {
    $sendMail = $this->sendMail->handle($request);

    return response()->json($sendMail, 200);
  }
}
