<?php

namespace App\Http\Controllers\Ppob\Games;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Repositories\Ppob\Games\GamesRepository;

class GameFlashController extends Controller
{
    protected $games;

    public function __construct(GamesRepository $games)
    {
        $this->games = $games;
    }

    public function gameDetail(Request $request, $slug)
    {

        $data = $this->games->getDetail($request, $slug);

        return $data;
    }

    // public function inquiry(Request $request)
    // {
    //     $user_id = auth('api')->id();
    //     $validator = Validator::make($request->all(), [
    //         'product_id' => 'required',
    //         'customer_phone' => 'required',
    //         // 'reff_number' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         return [
    //             'success' => false,
    //             'response_code' => 404,
    //             'message' => $validator->messages()->first(),
    //         ];
    //     }

    //     $data = $this->games->inquiry($request, $user_id);

    //     return $data;
    // }

    public function addOrder (Request $request){
        $response = init_transaction_data($request);
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'product_code' => 'required',
            'customer_id' => 'required',
            'customer_phone' => 'required',
        ]);

        if ($validator->fails()) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = $validator->messages()->first();

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        $order_info = [
            'user_id' => $user->id,
            'reff_no' => GenerateOrderId('GO'),
            'idcust' => $request->customer_id,
            'product_code' => $request->product_code,
            'phone' => $request->customer_phone
        ];

        $inquiry = $this->games->addOrder($order_info);

        if (isset($inquiry) && !$inquiry['status']) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = $inquiry['message'] ?? $inquiry;

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        $response['response']['success'] = true;
        $response['response']['response_code'] = 200;
        $response['response']['message'] = $inquiry['message'];
        $response['response']['data'] = $inquiry['data'];

        return Response($response['response'])->header('Content-Type', 'application/json');
    }

    public function topUp(Request $request){
        $url = ENV('PORTALPULSA_URL');
        $userid = ENV('PORTALPULSA_ID');
        $key = ENV('PORTALPULSA_KEY');
        $secret = ENV('PORTALPULSA_SECRET');

        $header = array(
        'portal-userid: '.$userid.'',
        'portal-key: '.$key.'', // lihat hasil autogenerate di member area
        'portal-secret: '.$secret.'', // lihat hasil autogenerate di member area
        );

        $data = array(
        'inquiry' => 'I', // konstan
        'code' => $request->input('code'), // kode produk
        'phone' => $request->input('phone'), // nohp pembeli
        'idcust' => $request->input('idcust'), // Diisi jika produk memerlukan IDcust seperti: Unlock/Aktivasi Voucher, Game Online (FF, ML, PUBG, dll)
        'trxid_api' => $request->input('trxid_api'), // Trxid / Reffid dari sisi client
        'no' => $request->input('no'), // untuk isi lebih dari 1x dlm sehari, isi urutan 1,2,3,4,dst
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POSTREDIR, CURL_REDIR_POST_ALL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);

        echo $result;
    }

    public function topUpRajaBiller(Request $request){
        $url = ENV('RAJABILLER_URL');

        $header = array(
        'Content-Type: application/json'
        );

        $param = array(
            'method' => 'rajabiller.game',
            'uid' => ENV('RAJABILLER_UID'),
            'pin' => ENV('RAJABILLER_PIN'),
            'no_hp' => $request->input('idcust'),
            'kode_produk' => $request->input('code'),
            'ref1' => $request->input('reff')
            );

        $response = Http::withHeaders($header)->post($url, $param);
        return $response;
        $data = $response['DATA'];
    }
}
