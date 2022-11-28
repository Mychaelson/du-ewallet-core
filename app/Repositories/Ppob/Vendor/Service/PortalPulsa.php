<?php

namespace App\Repositories\Ppob\Vendor\Service;

use Illuminate\Support\Facades\Log;

class PortalPulsa
{
    public $params = [];
    public $response = [];
    protected $user_id;
    protected $user_key;
    protected $user_secret;
    protected $api_url;

    public function __construct()
    {
        $this->api_url = ENV('PORTALPULSA_URL');
        $this->user_id = ENV('PORTALPULSA_ID');
        $this->user_key = ENV('PORTALPULSA_KEY');
        $this->user_secret = ENV('PORTALPULSA_SECRET');
    }

    public function setParams(array $params = [])
    {
        $this->params = $params;
        return $this;        
    }
    
    public function inquiry()
    {
        return $this->run();
    }

    public function run()
    {
        $data = $this->buildParams();
        $response = $this->curl($data);
        return [
            'success' => $response['result'] === 'success'? true: false,
            'status' => $response['result'] === 'success'? 'pending': 'failed',
            'request_data' => $data,
            'data' => [
                'customer_phone' => $this->params['customer_phone'],
                'serial_number' => '',
                'note' => $response['message']
            ],
            'base_price' => null,
            'response_data' => $response
        ];
    }

    public function buildParams()
    {
        $phone =  str_replace('+','',$this->params['customer_phone']);
        
        if(isset($this->params['action'])){

            if ($this->params['action'] == 'harga') {
                $data = array(
                    'inquiry' => 'HARGA', // konstan
                    'code' => $this->params['code'], // pilihan: pln, pulsa, game
                );
                
            }else if ($this->params['action'] == 'status') {
                 $data = array(
                    'inquiry' => 'STATUS', // konstan
                    'trxid_api' => $this->params['reff_number'], // Trxid atau Reffid dari sisi client saat transaksi pengisian
                );
                
            }else if ($this->params['action'] == 'balance') {
                $data = array(
                    'inquiry' => 'S', // konstan
                );
                
            }else if ($this->params['action'] == 'deposit') {
                $data = array(
                    'inquiry' => 'D', // konstan
                    'bank' => $this->params['bank'], // bank tersedia: bca, bni, mandiri, bri, muamalat
                    'nominal' => $this->params['nominal'], // jumlah request
                );
    
            }else if ($this->params['action'] == 'pln') {
                $data = array(
                    'inquiry' => 'PLN', // konstan
                    'code' => $this->params['code'], // kode produk
                    'phone' => $phone, // nohp pembeli
                    'idcust' => $this->params['customer_id'], // nomor meter atau id pln
                    'trxid_api' => $this->params['reff_number'], // Trxid / Reffid dari sisi client
                    'no' => '1', // untuk isi lebih dari 1x dlm sehari, isi urutan 2,3,4,dst
                );
            
            }else if ($this->params['action'] == 'games') {
                // tidak ada di portal pulsa
                $data = array(
                    'inquiry' => 'I', // Isi Pulsa / TopUp
                    'code' => $this->params['code'],
                    'phone' => $phone, 
                    'idcust' => $phone, // Diisi jika produk memerlukan IDcust seperti: Unlock/Aktivasi Voucher, Game Online (FF, ML, PUBG, dll)
                    'trxid_api' =>  $this->params['reff_number'], 
                    'no' => '1', // untuk isi lebih dari 1x dlm sehari, isi urutan 1,2,3,4,dst
                );
            } else {
                $data = [];
            }
        }else {
            $data = array(
                'inquiry' => 'I', // Isi Pulsa / TopUp
                'code' => $this->params['code'],
                'phone' => $phone, 
                // 'idcust' => '6173859206', // Diisi jika produk memerlukan IDcust seperti: Unlock/Aktivasi Voucher, Game Online (FF, ML, PUBG, dll)
                'trxid_api' =>  $this->params['reff_number'], 
                'no' => '1', // untuk isi lebih dari 1x dlm sehari, isi urutan 1,2,3,4,dst
            );
        }
        return $data;

        
        
    }

    public function curl($data)
    {
        $headers = [
            "portal-userid: {$this->user_id}",
            "portal-key: {$this->user_key}",
            "portal-secret: {$this->user_secret}"
        ];

        // dd($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POSTREDIR, CURL_REDIR_POST_ALL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);

        $curl_data = json_decode($result, TRUE);
        
        return $curl_data;
    }

    public function setParamsTopupPulsa($request)
    {
        $data = array(
            'inquiry' => 'I', // Isi Pulsa / TopUp
            'code' => $request['product_code'],
            'phone' => $request['phone'], 
            'idcust' => '',
            'trxid_api' => $request['reff_number'], 
            'no' => $request['no'], // untuk isi lebih dari 1x dlm sehari, isi urutan 1,2,3,4,dst
        );

        $inquiry = $this->curl($data);

        return $this->checkInquiry($inquiry);
    }

    public function setTransactionInquiry ($inquiry, $total)
    {
        $transactionInfo = [
            'price_sell' => 0,
            'price_service' => 0,
            'admin_fee_service' => 0,
            'profit' => 0
        ];

        return $transactionInfo;
    }

    public function setParamsGames ($request){
        $data = array(
            'inquiry' => 'I', // konstan
            'code' => $request['product_code'], // kode produk
            'phone' => '+62'.$request['phone'], // nohp pembeli
            'idcust' => $request['idcust'], // Diisi jika produk memerlukan IDcust seperti: Unlock/Aktivasi Voucher, Game Online (FF, ML, PUBG, dll)
            'trxid_api' => $request['reff_number'], // Trxid / Reffid dari sisi client
            'no' => $request['no'], // untuk isi lebih dari 1x dlm sehari, isi urutan 1,2,3,4,dst
        );

        $inquiry = $this->curl($data);

        return $this->checkInquiry($inquiry);
    }

    private function checkInquiry ($inquiry){
        if (!isset($inquiry)) {
            return [
                'status' => false,
                'message' => trans('error.inquiry_failed'),
                'data' => []
            ];
        }

        if ($inquiry['result'] == 'failed') {
            return [
                'status' => false,
                'message' => $inquiry['message'],
                'data' => $inquiry
            ];
        }

        return [
            'status' => true,
            'data' => $inquiry
        ];
    }
}