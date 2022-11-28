<?php

namespace App\Repositories\Services\Mail\EmailPortal;

use Illuminate\Support\Facades\Validator;

class EmailPortalApi
{
	public $endPoint;
	public $apiUser;
    public $apiPass;
	public $from;

	public $rawUrl;
	public $rawRequest;
	public $rawResponse;
	public $error;
	public $info;

    public function __construct()
    {
        $config = config('services.emailportal');
        $this->endPoint = $config['endpoint'];
        $this->apiUser = $config['api_user'];
        $this->apiPass = $config['api_pass'];
        $this->from = $config['from'];
    }

    public function send($data)
    {
    	$this->rawUrl = $this->endPoint."/sendmail";
    	$this->rawRequest = $data;

        //should validate data
        $validateData = $this->validate($data);
        if (!$validateData)
            return false;

        $data['from'] = $this->from;
    	$header = [
    		"api_user: ".$this->apiUser,
    		"api_pass: ".$this->apiPass,
    		"Content-Type: application/json"
    	];

    	$client = curl_init();
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($client, CURLOPT_ENCODING, "");
        curl_setopt($client, CURLOPT_MAXREDIRS, 10);
        curl_setopt($client, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($client, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($client, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($client, CURLOPT_TIMEOUT, 3);
        curl_setopt($client, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($client, CURLOPT_URL, $this->rawUrl);
        curl_setopt($client, CURLOPT_HTTPHEADER, $header);
        curl_setopt($client, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($client);
        $error = curl_error($client);
        $info = curl_getinfo($client);
        curl_close($client);

        $result = $this->parse($response, $error, $info);
        return $result;
    }

    private function validate($data)
    {
        $rules = array(
            'email' => 'required|email',
            'from_name' => 'required',
            'subject' => 'required',
            'content' => 'required',
        );

        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            $this->rawResponse = $validator->errors()->messages();
            return false;
        }

        return true;
    }

    private function parse($response, $error, $info)
    {
        $this->rawResponse = $result = json_decode($response, true) ?? $response;
        $this->error = $error;
        $this->info = $info;

        if (!is_null($result) && isset($result['status']) && $result['status'] == 'success')
            return true;

        return false;
    }
}
