<?php

namespace App\Http\Controllers\Ppob\Cellular;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Resources\Ppob\Data\DataResource as ResultResource;
use App\Repositories\Ppob\Cellular\CellularRepository;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Ppob\Base\PpobRepository;
use App\Repositories\Payment\BillRepository;
use App\Repositories\Ppob\Vendor\Service\RajaBiller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use DB;

class CellularController extends Controller
{
    protected $cellular, $ppobRepository, $billRepository, $rajaBillerRepository;

    public function __construct(CellularRepository $cellular, PpobRepository $ppobRepository, BillRepository $billRepository, RajaBiller $rajaBillerRepository)
    {
        $this->cellular = $cellular;
        $this->ppob = $ppobRepository;
        $this->bill = $billRepository;
        $this->rajaBillerRepository = $rajaBillerRepository;
    }

    public function addOrder(Request $request)
    {
        $user_id = auth('api')->id();
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'customer_phone' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'response_code' => 404,
                'message' => $validator->messages()->first(),
            ];
        }

        $data = $this->cellular->addOrder($request, $user_id);
            
        return $data;
    }

    public function inquiry(Request $request)
    {
        $user_id = auth('api')->user();
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'customer_phone' => 'required',

        ]);
        if ($validator->fails()) {
            return [
                'success' => false,
                'response_code' => 404,
                'message' => $validator->messages()->first(),
            ];
        }

        $data = $this->cellular->inquiry($request, $user_id);

        return $data;
    }

    public function getProduct(Request $request)
    {       
        $result = null;
        if($request->input('vendor') == 'rajaBill'){
            $result= $this->rajaBill($request);
        }else if($request->input('vendor') == 'portalPulsa'){
            $result= $this->portalPulsa($request);
        }
        return $result;
    }

    private function rajaBill($request){
        $group = array('GAME ONLINE IN', 'XL', 'TELKOMSEL', 'SMART', 'KARTU3', 'ISAT', 'FREN', 'AXIS / XL','ASURANSI','GAME ONLINE', 'PDAM');
        $url = ENV('RAJABILLER_URL');

        $header = array(
        'Content-Type: application/json'
        );
        $result= array();

        foreach($group as $row){
            $param = array(
                'method' => $request->method,
                'uid' => ENV('RAJABILLER_UID'),
                'pin' => ENV('RAJABILLER_PIN'),
                'group' => $row,
                'produk' => [],
                );
    
            $response = Http::withHeaders($header)->post($url, $param);
            
            $data = $response['DATA'];
            $result[] = $data;
            //DB::table('ppob.product_rajabillers')->insert($data);
        }
        return $result;
        //return "Data has been proccess";
    }

    private function portalPulsa($request){
        $url = ENV('PORTALPULSA_URL');
        $id = ENV('PORTALPULSA_ID');
        $key = ENV('PORTALPULSA_KEY');
        $secret = ENV('PORTALPULSA_SECRET');

        $header = array(
            'portal-userid: '.$id,
            'portal-key: '.$key,
            'portal-secret: '.$secret,
        );

        $param = array(
        'inquiry' => 'HARGA', // konstan
        'code' => $request->code, // pilihan: pln, pulsa, game
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POSTREDIR, CURL_REDIR_POST_ALL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $response = curl_exec($ch);

        $result = json_decode($response, TRUE);

        if($result['result'] != 'success'){
            return 'No Data Record';
        }

        //DB::table('ppob.product_portalpulsa')->insert($result['message']);

        return $result['message'];

        
    }

    public function topupPulsa(Request $request)
    {
        // get data from db for product and vendor
        // the condition check for the vendor
        $result = null;
        if($request->input('vendor') == 'topupPulsaRajaBiller'){
            $result = $this->rajaBillerRepository->setParamsTopupPulsa($request);
        }else if($request->input('vendor') == 'topupPulsaPortalPulsa'){
            $result= $this->topupPulsaPortalPulsa($request);
        }
        return $result;
    }

    private function topupPulsaPortalPulsa(Request $request)
    {  
        return $request;
    }

    public function addOrderV2 (Request $request)
    {
        $response = init_transaction_data($request);
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'product_code' => 'required',
            'phone_number' => 'required'
        ]);

        if ($validator->fails()) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = $validator->messages()->first();

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        $order_info = [
            'user_id' => $user->id,
            'phone' => cleanphone($request->phone_number),
            'reff_no' => GenerateOrderId('CELL'),
            'product_code' => $request->product_code
        ];

        $inquiry = $this->cellular->addOrderV2($order_info);

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
}
