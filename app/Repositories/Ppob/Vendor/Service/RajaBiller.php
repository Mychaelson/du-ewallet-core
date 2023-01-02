<?php

namespace App\Repositories\Ppob\Vendor\Service;

use Illuminate\Support\Facades\Http;

class RajaBiller
{
    protected $api_url;
    protected $uid;
    protected $pin;
    public function __construct()
    {
        $this->api_url = ENV('RAJABILLER_URL');
        $this->uid = ENV('RAJABILLER_UID');
        $this->pin = ENV('RAJABILLER_PIN');
    }

    public function setParamsTopupPulsa ($request){
      $param = array(
        'method' => 'rajabiller.pulsa',
        'uid' => $this->uid,
        'pin' => $this->pin,
        'no_hp' => $request['phone'],
        'kode_produk' => $request['product_code'],
        'ref1' => $request['reff_number'],
      );

      $inquiry = $this->callRajaBiller($param);

      $inquiry = json_decode($inquiry, true);
      if (!isset($inquiry)) {
          return [
              'status' => false,
              'message' => trans('error.inquiry_failed'),
              'data' => []
          ];
      }

      if (isset($inquiry['error'])) {
          return [
              'status' => false,
              'message' => $inquiry['error'],
              'data' => $inquiry
          ];
      }

      if ($inquiry['STATUS'] != '00') {
        return [
            'status' => false,
            'message' => $inquiry['KET'],
            'data' => $inquiry
        ];
      }

      return [
          'status' => true,
          'data' => $inquiry
      ];
    }

    public function setParamsBpjsInquiry ($request){
      $param = array(
        'method' => 'rajabiller.bpjsinq',
        'uid' => $this->uid,
        'pin' => $this->pin,
        'kode_produk' => $request['product_service_code'],
        'periode' => $request['periode'],
        'ref1' => $request['reff_no'],
        'idpel' => $request['bpjsMemberId'],
      );

      return $this->callRajaBiller($param);
    }

    public function setParamsBpjsPayment ($request){
      $param = array(
        'method' => "rajabiller.bpjspay",
        'uid' => $this->uid,
        'pin' => $this->pin,
        'kode_produk' => $request['KODE_PRODUK'],
        'periode' => $request['PERIODE'],
        'ref1' => $request['REF1'],
        'ref2' => $request['REF2'],
        'nominal' => $request['NOMINAL'],
        'no_hp' => $request['phone'],
        'idpel1' => $request['IDPEL1']
      );

      $payment = $this->callRajaBiller($param);
      
      return $this->checkStatus($payment);
    }

    public function setParamsPdamPayment ($request)
    {
      $param = array(
        'method' => "rajabiller.paydetail",
        'uid' => $this->uid,
        'pin' => $this->pin,
        'idpel1' => $request['IDPEL1'],
        'idpel2' => $request['IDPEL2'],
        'idpel3' => $request['IDPEL3'],
        'kode_produk' => $request['KODE_PRODUK'],
        'ref1' => $request['REF1'],
        'ref2' => $request['REF2'],
        'nominal' => $request['NOMINAL'],
        'ref3' => $request['REF3']
      );

      $payment = $this->callRajaBiller($param);
      
      return $this->checkStatus($payment);
    }

    public function setParamsPln ($request){
      $param = array(
        'method' => 'rajabiller.beli',
        'uid' => $this->uid,
        'pin' => $this->pin,
        'kode_produk' => $request['product_code'],
        'idpel' => $request['pln_number'],
        'nominal' => $request['nominal'],
        'ref1' => $request['reff_number'],
      );

      $inquiry = $this->callRajaBiller($param);

      return $this->checkStatus($inquiry);
    }

    public function setParamsPdam ($request){
      $param = array(
        'method' => 'rajabiller.inq',
        'uid' => $this->uid,
        'pin' => $this->pin,            
        'idpel1' => $request['pdam_number'],
        "idpel2" => "",
        "idpel3" => "",
        'kode_produk' => $request['product_code'],
        'ref1' => $request['reff_number'],
      );

      return $this->callRajaBiller($param);
    }

    public function setParamsGames ($request){
      $param = array(
        'method' => 'rajabiller.game',
        'uid' => $this->uid,
        'pin' => $this->pin,
        'no_hp' => $request['idcust'],
        'kode_produk' => $request['product_code'],
        'ref1' => $request['reff_number'],
      );
      
      $inquiry = $this->callRajaBiller($param);

      return $this->checkStatus($inquiry);
    }

    private function callRajaBiller($param)
    {  
        $header = array(
          'Content-Type: application/json'
        );

        $response = Http::withHeaders($header)->post($this->api_url, $param);
        return $response;
    }

    public function setTransactionInquiry($inquiry, $total, $isPostPaid = false){
      $transactionInfo = [
        // 'price_sell' => $inquiry['NOMINAL'],
        'price_service' => $inquiry['NOMINAL'],
        'admin_fee_service' => 0
      ];

      if ($isPostPaid) {
        $transactionInfo['price_sell'] = $inquiry['NOMINAL'];
      }

      if (isset($inquiry['ADMIN'])) {
        $transactionInfo['admin_fee_service'] = $inquiry['ADMIN'];
      }

      $transactionInfo['profit'] = $total - ($transactionInfo['price_service'] + $transactionInfo['admin_fee_service']);

      return $transactionInfo;
    }

    private function checkStatus ($inquiryInfo)
    {
      $inquiry = json_decode($inquiryInfo, true);
      if (!isset($inquiry)) {
          return [
              'status' => false,
              'message' => trans('error.inquiry_failed'),
              'data' => []
          ];
      }

      if (isset($inquiry['error'])) {
          return [
              'status' => false,
              'message' => $inquiry['error'],
              'data' => $inquiry
          ];
      }

      if ($inquiry['STATUS'] != '00') {
        return [
            'status' => false,
            'message' => $inquiry['KET'],
            'data' => $inquiry
        ];
      }

      return [
          'status' => true,
          'data' => $inquiry
      ];
    }

    public function resInquiry ($inquiryInfo) {
      $inquiry = json_decode($inquiryInfo, true);

      $data = [
        'customer_name' => $inquiry['NAMA_PELANGGAN'],
        'customer_id' => $inquiry['IDPEL1'],
        'period' => $inquiry['PERIODE'],
      ];

      return $data;
    }

    public function formatPaymentToken ($resPaymentInfo){
      $data = [
        'nama_pelanggan' => $resPaymentInfo['NAMA_PELANGGAN'],
        'invoice_no' => $resPaymentInfo['REF1'],
        'token' => $resPaymentInfo['DETAIL']['TOKEN'],
        'kwh' => (double) $resPaymentInfo['DETAIL']['PURCHASEDKWHUNIT'] / 100,
      ];

      return $data;
    }
}