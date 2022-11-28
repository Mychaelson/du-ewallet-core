<?php

namespace App\Notifications\Accounts;

use App\Repositories\Services\Mail\EmailPortal\EmailPortalApi;

class KycNotification
{
	function __construct(
        private EmailPortalApi $epApi,
	)
	{}

	public function handle($data)
	{
        $company = config('company.info');
        $viewData['company'] = $company['name'];
        $viewData['brand'] = $company['brand'];
        $viewData['domain'] = $company['domain'];
        $viewData['assets'] = $company['assets'];
        $viewData['username'] = $data['username'];
        $view = $data['active'] == 1 ? view('mail.kyc-approve', $viewData)->render() : view('mail.kyc-reject', $viewData)->render() ;

        $mail['email'] = $data['email'];
        $mail['from_name'] = $company['brand'];
        $mail['subject'] = 'Account Verification';
        $mail['content'] = $view;        
        $sendMail = $this->epApi->send($mail);

        return $this->epApi;
	}
}