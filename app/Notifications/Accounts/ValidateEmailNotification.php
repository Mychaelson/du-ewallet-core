<?php

namespace App\Notifications\Accounts;

use App\Repositories\Services\Mail\EmailPortal\EmailPortalApi;

class ValidateEmailNotification
{
	function __construct(
        private EmailPortalApi $epApi,
	)
	{}

	public function handle($data)
	{
        $email = $data['email'];
        $otp = $data['otp'];
        $company = config('company.info');

        $viewData['company'] = $company['name'];
        $viewData['brand'] = $company['brand'];
        $viewData['domain'] = $company['domain'];
        $viewData['assets'] = $company['assets'];
        $viewData['vcode'] = $otp->token;
        $view = view('mail.verify-email-code', $viewData)->render();

        $mail['email'] = $email;
        $mail['from_name'] = $company['brand'];
        $mail['subject'] = 'Email Verification';
        $mail['content'] = $view;        
        $sendMail = $this->epApi->send($mail);

        return $this->epApi;
	}
}