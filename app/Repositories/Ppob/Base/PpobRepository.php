<?php

namespace App\Repositories\Ppob\Base;

use App\Models\Ppob\DigitalCategories;
use App\Models\Ppob\ProductV2;
use App\Models\Ppob\TransactionV2;
use App\Resources\Ppob\CategoryProduct\CategoryProductCollection as Resultcollection;
use App\Resources\Ppob\Data\DataResource as ResultResource;
use App\Repositories\Ppob\Vendor\Service\PortalPulsa;
use App\Resources\Ppob\Product\ProductResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Builder;
use League\OAuth1\Client\Server\Tumblr;

class PpobRepository
{
    private $transactionV2;
    private $product;
    private $portalPulsa;

    public function __construct(
        TransactionV2 $transactionV2,
        ProductV2 $product,
        PortalPulsa $portalPulsa
    )
    {
        $this->transactionV2 = $transactionV2;
        $this->product = $product;
    }

    public function getList($slug,$request)
    {
        $category = DigitalCategories::where('slug',$slug)->first();

        if(!$category)
            return response()->json(['success'=>false, 'response_code' => 404, 'message' => trans('error.data_not_found_in_category')], 404);

        
        $subCategory = $category->childs()->active();
        if($request->has('q')){
            $q = $request->q;
            $subCategory = $subCategory->where('name','ILIKE','%'.$q.'%');
        }

        if ($subCategory && $subCategory->count() > 0) {
            $cat = $subCategory->get();
            return (new Resultcollection($cat))
                ->response()
                ->header('X-Ip-Request', $request->ip());
        }else{
            return response()->json(['success'=>false, 'response_code' => 404, 'message' => trans('error.data_not_found')], 404);
        }

    }

    public function insertIdDigitalTransaksi($param)
    {
        $data = DB::table('ppob.digital_transactions')->insertGetId($param);

        return $data;
    }

    public function insertDigitalTransaksi($param)
    {
        $data = DB::table('ppob.digital_transactions')->insert($param);

        return $data;
    }

    public function updateDigitalTransaksi($oder_id,$params)
    {
        $data = DB::table('ppob.digital_transactions')->where('order_id', $oder_id)->update($params);

        return $data;
    }

    public function getDetail($id)
    {
        $data = DB::table('ppob.digital_transactions')->where('id',$id)->first();

        return $data;
    }

    public function getProduct($code)
    {
        $data = DB::table('ppob.digital_products')->where('code',$code)->first();

        return $data;
    }

    public function getDetailByOrder_id($order_id)
    {
        $data = DB::table('ppob.digital_transactions')->where('order_id',$order_id)->first();

        return $data;
    }

    public function insertTransaction($data){
        $data = $this->transactionV2->insertGetId($data);
        
        return $data;
    }

    public function getTransactionById($transactionId, $productCategory){
        $result = $this->transactionV2->where('id', $transactionId)->first();
        $product = $this->product->where('code', $result->product_code)->first();

        $transactionDataInq = json_decode($result->req_inquiry, true);

        $part_inv = explode('-', $result->invoice_no);
        $phone = null;

        if($part_inv[0] == 'CELL'){
            $req_inq = json_decode($result->req_inquiry, true);
            $phone= $req_inq['phone'];
        }
        
        $data = [
            "id"                => $result->id,
            "user_id"           => $result->user_id,
            "product_code"      => $result->product_code,
            "label_id"          => $result->label_id,
            "order_id"          => $result->invoice_no,
            "product_type"      => $result->product_type,
            "price"             => (double) $result->price_sell,
            "admin_fee"         => (double) $result->admin_fee,
            "discount"          => (double) $result->discount,
            "total"             => (double) $result->total,
            "price_service"     => (double) $result->price_service,
            "admin_fee_service" => (double) $result->admin_fee_service,
            "profit"            => (double) $result->profit,
            "status"            => (string) $result->status,
            "service_id"        => (double) $result->service_id,
            "req_inquiry"       => (double) $result->req_inquiry,
            "res_inquiry"       => (double) $result->res_inquiry,
            "req_payment"       => (double) $result->req_payment,
            "res_payment"       => (double) $result->res_payment,
            "phone"             => $phone,
            "currency"          => 'IDR',
            "result"            => (object) [],
            "product"           => new ProductResource($product),
            "created_at"        => date_format($result->created_at, "Y-m-d m:s"),
            "updated_at"        => date_format($result->updated_at, "Y-m-d m:s")
        ];

        if ($productCategory == 'plnToken') {
            $data['result'] = (object) [
                'customer_id' => $transactionDataInq['pln_number'],
                'customer_name' => null,
                'customer_phone' => $transactionDataInq['phone'],
                'period' => null,
                'registration_date' => null,
                'note' => null
            ];
        }

        if ($productCategory == 'games') {
            $data['result'] = (object) [
                'customer_id' => $transactionDataInq['idcust'],
                'customer_name' => null,
                'customer_phone' => $transactionDataInq['phone'],
                'period' => null,
            ];
        }

        if ($productCategory == 'bpjsKes') {
            $transactionDataRes = (new $transactionDataInq['service'])->resInquiry($result->res_inquiry);

            $data['result'] = (object) [
                'customer_id' => $transactionDataRes['customer_id'],
                'customer_name' => $transactionDataRes['customer_name'],
                'customer_phone' => $transactionDataInq['phone'],
                'period' => $transactionDataRes['period'],
            ];
        }

        if ($productCategory == 'pdam') {
            $transactionDataRes = (new $transactionDataInq['service'])->resInquiry($result->res_inquiry);
            $data['result'] = (object) [
                'customer_id' => $transactionDataRes['customer_id'],
                'customer_name' => $transactionDataRes['customer_name'],
                'customer_phone' => $transactionDataInq['phone'],
                'period' => $transactionDataRes['period'],
                'provider_name' => $transactionDataInq['provider']
            ];
        }
        
        return $data;
    }

    public function updateTransactionById ($transactionId, $data){
        $data = $this->transactionV2->where('id', $transactionId)->update($data);

        return $data;
    }

    public function findProductByProductCode($productCode){
        $data = $this->product
                    ->join('ppob.service_v2', 'ppob.product_v2.service_id', '=', 'ppob.service_v2.id')
                    ->join('ppob.product_services', function($join){
                        $join->on('ppob.product_services.product_code', 'ppob.product_v2.code');
                        $join->on('ppob.product_services.service_id', 'ppob.product_v2.service_id');
                    })
                    ->select(
                        'ppob.product_v2.*', 
                        'ppob.service_v2.name as service_name', 
                        'ppob.service_v2.path as service_path', 
                        'ppob.service_v2.id as service_id',
                        'ppob.product_services.base_price as service_base_price',
                        'ppob.product_services.admin_fee as service_admin_fee',
                        'ppob.product_services.code as service_product_code',
                        'ppob.product_services.status as service_status',
                    )
                    ->where('ppob.product_v2.code', $productCode)  
                    ->first();
        
        return $data;
    }

    public function getTransactionByInvoiceNo ($invoice_no){
        $data = $this->transactionV2->where('invoice_no', $invoice_no)->first();

        return $data;
    }

    public function findProductInfo ($productCode){
        $data = $this->product->where('code', $productCode)->first();

        return $data;
    }

    public function updateProduct(){
        //update product portal pulsa
        $portalPulsa = $this->updateProductPortalPulsa();

        //update product raja biller
        $rajaBiller = $this->updateProductRajaBiller();

        //update product local
        $result = DB::table('ppob.product_services')
            ->select('product_code', 
                        'service_id',
                        'code',
                        'base_price',
                        'status')
            ->selectRaw('row_number() over (partition by product_code order by status desc, base_price asc)')
            ->orderBy('product_code')
            ->chunk(100, function ($data) {
                foreach ($data as $value) {
                    if($value->row_number == 1){
                        $update = DB::table('ppob.product_v2')
                        ->where('code', $value->product_code)
                        ->update(
                            [
                                'status' => $value->status,
                                'service_id' => $value->service_id,
                                'price_buy' => $value->base_price,
                                'updated_at' => date('Y-m-d H:i:s')
                            ])
                        ;
                    }
                }
            });

        return true;
    }

    private function updateProductPortalPulsa(){
        $url = ENV('PORTALPULSA_URL');
        $id = ENV('PORTALPULSA_ID');
        $key = ENV('PORTALPULSA_KEY');
        $secret = ENV('PORTALPULSA_SECRET');

        $header = array(
            'portal-userid: '.$id,
            'portal-key: '.$key,
            'portal-secret: '.$secret,
        );

        $code = ['PLN', 'PULSA', 'GAME'];
        $result = [];
        DB::table('ppob.product_portalpulsa')->truncate();
        foreach($code as $value){
            $param = array(
                'inquiry' => 'HARGA', // konstan
                'code' => $value, // pilihan: pln, pulsa, game
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
        
                $response = json_decode($response, TRUE);
                DB::table('ppob.product_portalpulsa')->insert($response['message']);
        }

        $result = DB::table('ppob.product_portalpulsa')
            ->orderBy('provider')
            ->chunk(100, function ($data) {
                foreach ($data as $value) {
                    if($value->status =='normal'){
                        $status = 1;
                    }else{
                        $status = 0;
                    }

                    $update = DB::table('ppob.product_services')
                                    ->where('code', $value->code)
                                    ->where('service_id',1)
                                    ->where(function ($query) use ($value, $status){
                                        $query->where('base_price', '<>', $value->price)
                                                ->orWhere('status', '<>', $status);
                                    })
                                    ->update(
                                        ['status' => $status, 
                                        'base_price' => $value->price,
                                        'updated_at' => date('Y-m-d H:i:s')
                                        ])
                                    ;              
                }
            });       

        return $result;
    }

    private function updateProductRajaBiller(){
        $group = array('GAME ONLINE IN', 'XL', 'TELKOMSEL', 'SMART', 'KARTU3', 'ISAT', 'FREN', 'AXIS / XL','ASURANSI','GAME ONLINE', 'PDAM');
        $url = ENV('RAJABILLER_URL');

        $header = array(
        'Content-Type: application/json'
        );
        $result= array();
        DB::table('ppob.product_rajabillers')->truncate();
        foreach($group as $row){
            $param = array(
                'method' => 'rajabiller.cekharga_gp',
                'uid' => ENV('RAJABILLER_UID'),
                'pin' => ENV('RAJABILLER_PIN'),
                'group' => $row,
                'produk' => [],
                );
    
            $response = Http::withHeaders($header)->post($url, $param);
            $data = $response['DATA'];
            $result[] = $data;
            DB::table('ppob.product_rajabillers')->insert($data);
        }

        $result = DB::table('ppob.product_rajabillers')
            ->orderBy('idproduk')
            ->chunk(100, function ($data) {
                foreach ($data as $value) {
                    if($value->status =='AKTIF'){
                        $status = 1;
                    }else{
                        $status = 0;
                    }

                    $update = DB::table('ppob.product_services')
                                    ->where('code', $value->idproduk)
                                    ->where('service_id',2)
                                    ->where(function ($query) use ($value, $status){
                                        $query->where('base_price', '<>', $value->harga_jual)
                                                ->orWhere('status', '<>', $status)
                                                ->orWhere('admin_fee', '<>', $value->biaya_admin);
                                    })
                                    ->update(
                                        ['status' => $status, 
                                        'base_price' => $value->harga_jual,
                                        'admin_fee' => $value->biaya_admin,
                                        'updated_at' => date('Y-m-d H:i:s')
                                        ])
                                    ;              
                }
            });       
    }
    
    public function countTransaction ($productCode, $user_id)
    {
        $data = $this->transactionV2->where('user_id', $user_id)
                                    ->where('product_code', $productCode)
                                    ->where('status', 3)
                                    ->whereDay('created_at', date("d") )
                                    ->count()
        ;

        return $data;
    }

    public function updateTransactionByInvoice ($invoice, $data)
    {
        $data = $this->transactionV2->where('invoice_no', $invoice)->update($data);

        return $data;
    }
}
