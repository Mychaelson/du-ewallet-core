<?php

namespace App\Macros\Notifications;

use App\Repositories\Notification\TemplateRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailSender;

class SendMailMacro
{
	function __construct(
		private TemplateRepository $templateRepository,
		)
	{
	}

	// send unreqister
	public function handle($data)
	{
			$sender = $data->sender ??  "Nusapay";

			if(! in_array($data->channel_id, [2, 4])){
					return [
							'success' => false,
							'response_code' => 404,
							'message' => 'Unknown channel sender'];
			}

			$dataDecoration = $this->dataDecoration($data);

			if($data->channel_id === 2) {
				$where = ['channel_id' => 2, 'activity' => $data->activity];
				$template = $this->templateRepository->first($where);
				$temp = $template ? $template->path: 'mail.generalnon';
				if(is_array($data->to)){
						foreach ($data->to as $email) {
								$to = [
										[
												'email' => $email,
												'name' => $email,
										]
								];

								Mail::to($to)->send(new EmailSender($dataDecoration, $temp));
						}
					}else{
							$to = [
											[
													'email' => $data->to,
													'name' => $data->to,
											]
									];
							Mail::to($to)->send(new EmailSender($dataDecoration, $temp));
					}

			}

			return response()->json([
					'success'=> true,
					'response_code' => 200,
					'message' => 'Message sending..'
			], 200);

	}

	private function dataDecoration($request)
	{
			$title = $request->data['title'] ?? '';
			$content = $request->data['content'] ?? '';
			$url = $request->data['url'] ?? '';
			$image = $request->data['image'] ?? null;

			$data = $request->data;
			unset($data['title'],$data['content'],$data['url'],$data['image']);
			return [
					'activity' => $request->activity,
					'title' => $title,
					'content' => $content,
					'url' => $url,
					'image' => $image,
					'data' => $data
			];
	}
}
