<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Repositories\Payment\BillPaymentRepository;
use App\Repositories\Payment\BillRepository;
use App\Repositories\Payment\CardRepository;
use App\Repositories\Wallet\WalletsRepository;
use App\Repositories\Accounts\UsersRepository;
use App\Repositories\Wallet\WalletsTransactionsRepository;
use App\Repositories\Ppob\Cellular\CellularRepository;
use App\Repositories\Ppob\Base\PpobRepository;
use App\Repositories\Accounts\OTPRepository;
use App\Repositories\Setting\SiteParamsRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    private $cards_providers = [
        0 => ['AMERICAN_EXPRESS',   'American Express'],
        1 => ['DINERS_CLUB',        'Diners Club'],
        2 => ['DISCOVER',           'Discover'],
        3 => ['JCB',                'JCB'],
        4 => ['LASER',              'Laser'],
        5 => ['MAESTRO',            'Maestro'],
        6 => ['MASTERCARD',         'Mastercard'],
        7 => ['SOLO',               'Solo'],
        8 => ['UNIONPAY',           'China UnionPay '],
        9 => ['VISA',               'Visa'],
        10 => ['INTER_PAYMENT',     'InterPayment'],
        11 => ['INSTA_PAYMENT',     'InstaPayment'],
        12 => ['DANKORT',           'Dankort'],
    ];

    private $cards_rules = [
        //   +------------------------ Number Length
        //   |  +--------------------- Prefix Length
        //   |  |    +---------------- Value Type ( 1 Single, 2 Array, 3 Range )
        //   |  |    |  +------------- Provider Index ( see property $cards_providers )
        //   |  |    |  |   +--------- Value Query
        //   |  |    |  |   |
        12 => [
            2 => [
                [1, 5, 50],
                [3, 5, [56, 69]],
            ],
        ],
        13 => [
            2 => [
                [1, 5, 50],
                [3, 5, [56, 69]],
            ],
            1 => [
                [1, 9, 4],
            ],
        ],
        14 => [
            3 => [
                [1, 1, 309],
                [3, 1, [300, 305]],
            ],
            2 => [
                [1, 5, 50],
                [2, 1, [36, 38]],
                [3, 5, [56, 69]],
            ],
        ],
        15 => [
            4 => [
                [2, 3, [2131, 1800]],
            ],
            2 => [
                [1, 5, 50],
                [2, 0, [34, 37]],
                [3, 5, [56, 69]],
            ],
            1 => [
                [1, 2, 5],
            ],
        ],
        16 => [
            6 => [
                [3, 2, [622126, 622925]],
            ],
            4 => [
                [1, 2, 6011],
                [1, 12, 5019],
                [3, 3, [3528, 3589]],
            ],
            3 => [
                [1, 10, 636],
                [2, 11, [637, 638, 639]],
                [3, 2, [644, 649]],
            ],
            2 => [
                [1, 3, 35],
                [1, 2, 65],
                [1, 8, 62],
                [1, 5, 50],
                [2, 1, [38, 39]],
                // [2, 1, [54,55]], // Diners Club United States & Canada ( ignored due to duplicate with mastercard )
                [3, 5, [56, 69]],
                [3, 6, [51, 55]],
            ],
            1 => [
                [1, 9, 4],
            ],
        ],
        17 => [
            3 => [
                [1, 10, 636],
            ],
            2 => [
                [1, 8, 62],
                [1, 5, 50],
                [3, 5, [56, 69]],
            ],
        ],
        18 => [
            3 => [
                [1, 10, 636],
            ],
            2 => [
                [1, 8, 62],
                [1, 5, 50],
                [3, 5, [56, 69]],
            ],
        ],
        19 => [
            3 => [
                [1, 10, 636],
            ],
            2 => [
                [1, 8, 62],
                [1, 5, 50],
                [3, 5, [56, 69]],
            ],
            1 => [
                [1, 9, 4],
            ],
        ],
    ];

   

    private $userId;

    private $billRepository;

    private $billPaymentRepository;

    private $cardRepository;
    private $cellularRepository;
    private $ppobRepository;
    

    public function __construct(BillRepository $bill, 
    BillPaymentRepository $billPayment, 
    CardRepository $card, 
    CellularRepository $cellularRepository, 
    PpobRepository $ppobRepository, 
    private WalletsRepository $walletsRepository, 
    private UsersRepository $usersRepository, 
    private OTPRepository $otpRepository, 
    private WalletsTransactionsRepository $walletsTransactionsRepository,
    private SiteParamsRepository $siteParamsRepository)
    {
        $this->userId = auth('api')->id();
        $this->billRepository = $bill;
        $this->billPaymentRepository = $billPayment;
        $this->cardRepository = $card;
        $this->cellularRepository = $cellularRepository;
        $this->ppobRepository = $ppobRepository;
        
        
    }

    public function cards(Request $request)
    {
        $where = [
            'status' => $request->status,
            'user' => $this->userId,
            'visibility' => true,
        ];

        $perpage = $request->per_page ?? 10; // optional
        $page = $request->page - 1;

        $cards = $this->cardRepository->getPaginate($where, $page, $perpage);

        return $this->response(0, $cards);
    }

    public function card($id)
    {
        $where = [
            'user' => $this->userId,
            'id' => $id,
        ];

        $card = $this->cardRepository->get($where);
        if (! $card) {
            return $this->response(404, $card);
        }

        return $this->response(0, $card);
    }

    public function cardInfo($id)
    {
        $bank = $this->CCinfo($id);

        return $this->response(0, $bank);
    }

    public function cardCreate(Request $request)
    {
        $def = (object) [
            'user' => $this->userId,
            'visibility' => true,
            'exp_year' => $request->exp_year,
            'exp_month' => $request->exp_month,
            'number' => $request->number,
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $exp_month = $def->exp_year.'-'.$def->exp_month;
        $expiration = $exp_month.'-'.date('t', strtotime($exp_month.'-01')).' 23:59:59';
        $exp_time = strtotime($expiration);
        if ($exp_time < time()) {
            return $this->response(422, ['exp_year' => 'The card already expires']);
        }

        $def->bank = $this->CCinfo($def->number);
        if (isset($def->bank['provider'])) {
            $def->bank = json_encode($def->bank['provider']);
        } else {
            $def->bank = json_encode([]);
        }

        $where = ['user' => $this->userId, 'number' => $def->number];
        $card = $this->cardRepository->get($where);

        if (count($card) == 0) {
            $card = $this->cardRepository->create((array) $def);
        } else {
            $diff = [];
            foreach ($def as $key => $val) {
                if ($card[0]->$key != $val) {
                    $card[0]->$key = $diff[$key] = $val;
                }
            }
            if ($diff) {
                if (isset($diff['exp_year']) || isset($diff['exp_month'])) {
                    $diff['status'] = 1;
                }
                $where = ['id' => $card[0]->id];
                $this->cardRepository->update($where, $diff);
            }
        }

        return $this->response(0, $card);
    }

    public function cardDelete($id)
    {
        $where = ['user' => $this->userId, 'id' => $id, 'visibility' => true];
        $card = $this->cardRepository->get($where);
        if (! $card) {
            return $this->response(0, []);
        }

        $where = ['id' => $id];
        $update = ['visibility' => false];
        $this->cardRepository->update($where, $update);

        return $this->response(0, [], 'Success');
    }

    public function CCinfo($number)
    {
        $number = preg_replace('![^0-9]!', '', $number);
        $length = strlen($number);

        $rules = $this->cards_rules[$length] ?? null;
        if (! $rules) {
            return [];
        }

        $provider = null;
        foreach ($rules as $plen => $prefixes) {
            $num_pref = (int) substr($number, 0, $plen);

            foreach ($prefixes as $rule) {
                $val_opts = $rule[2];

                if ($rule[0] === 1) {
                    $val_opts = [$val_opts];
                } elseif ($rule[0] === 3) {
                    $val_opts = range($val_opts[0], $val_opts[1]);
                }
                if (! in_array($num_pref, $val_opts)) {
                    continue;
                }

                $provider = $this->cards_providers[$rule[1]];

                break 2;
            }
        }

        if (! $provider) {
            return [];
        }

        $sum = 0;
        $weight = 2;

        for ($i = $length - 2; $i >= 0; $i--) {
            $digit = $weight * $number[$i];
            $sum += floor($digit / 10) + $digit % 10;
            $weight = $weight % 2 + 1;
        }

        if ((10 - $sum % 10) % 10 != $number[$length - 1]) {
            return [];
        }

        return [
            'provider' => [
                'id' => $provider[0],
                'label' => $provider[1],
                'logo' => null,
            ],
            'number' => $number,
        ];
    }

    public function response($error, $data = [], $message = null)
    {
        $response = [
            'success' => $error == 0,
            'response_code' => 200,
            'message' => $message,
            'data' => $data,
            'total' => $error == 0 ? count($data) : 0,
        ];

        $headers['Connection'] = 'close';
        $headers['Content-Type'] = 'application/json';

        return response()->json($response, 200, $headers);
    }

    public function bills(Request $request)
    {
        $bill = $this->checkBill();
        if (! $bill) {
            return $this->response(404, []);
        }

        $where = $this->getOptionalRequest($request->all(), ['status', 'method']);
        $where['user'] = auth()->id();
        // $where['bill'] = $bill->id;

        $payments = $this->billRepository->search($where);

        if (count($payments) != 0 && ! isset($where['status'])) {
            $payments = $payments->where('status', '>', 0);
        }

        return $this->response(0, $payments);
    }

    public function bill($billId, Request $request)
    {
        $bill = $this->checkBill($billId);
        if (! $bill) {
            return $this->response(404, []);
        }

        $where = $this->getOptionalRequest($request->all(), ['status', 'method']);
        // $where['user'] = auth()->id();
        $where['bill'] = $bill->id;

        $payments = $this->billPaymentRepository->get($where);

        if (! isset($where['status'])) {
            $payments = $payments->where('status', '>', 0);
        }

        return $this->response(0, $payments);
    }

    public function billPayment($billId, $paymentId, Request $request)
    {
        $bill = $this->checkBill($billId);
        if (! $bill) {
            return $this->response(404, []);
        }

        $where = [
            'bill' => $bill->id,
            'id' => $paymentId,
        ];

        $payments = $this->billPaymentRepository->get($where);
        if (! $payments) {
            return $this->response(404, []);
        }

        return $this->response(0, $payments);
    }

    private $methods = [
        'gwallet' => [
            'internal' => true,
            // 'class' => 'NpBillGwallet\\Library\\Method',
        ],
        'nusaku' => [
            'internal' => true,
            // 'class' => 'NpBillNusaku\\Library\\Method',
        ],
        'promo' => [
            'internal' => true,
            // 'class' => 'NpBillPromo\\Library\\Method'
        ],
        'card' => [
            'internal' => false,
            // 'class' => 'NpModCard\\Library\\Method',
        ],
    ];

    public function billMethod($billId, Request $request)
    {
        $bill = $this->checkBill($billId);
        if (! $bill) {
            return $this->response(404, []);
        }

        $internal_total = 0;
        $method_used = [];
        $allow_internal = true;
        $allow_external = true;

        $where = ['bill' => $bill->id];
        $payments = $this->billPaymentRepository->get($where);
        foreach ($payments as $payment) {
            $method_used[] = $payment->method;
            if (! in_array($payment->status, ['1', '2'])) {
                continue;
            }

            if ($payment->internal) {
                $internal_total++;
                if ($internal_total == 3) {
                    $allow_internal = false;
                }
            } else {
                $allow_external = false;
            }
        }

        $result = [];
        foreach ($this->methods as $name => $method) {
            $result[$name] = null;

            // if (! $method['internal'] && ! $allow_external) {
            //     continue;
            // }
            // if ($method['internal'] && ! $allow_internal) {
            //     continue;
            // }
            // if (in_array($name, $method_used)) {
            //     continue;
            // }

            $res = [
                'name' => $name,
                'internal' => $method['internal'],
                'content' => null,
            ];

            if ($name == 'promo') { // WARNING
                if($bill->wallet == 'local' || $bill->currency == 'IDR')
                    $res['content'] = ['code' => 'Please provide promotion code'];

            }else if($name == 'card') {
                // $res['content'] = $this->getInfoCard($bill, $where);
                $res['content'] = [];

            }else if($name == 'nusaku') {
                $res['content'] = $this->getInfoNusaku($bill, $where);
            }

            if ($res['content'])
                $result[$name] = $res;
        }

        return response()->json(["success" => true,
            "response_code" => 200,
            "message" => "OK",
            "data" => $result,
            "errors" => null
        ]);

        return $this->response(0, $result, "OK");
    }

    public function getInfoNusaku($bill, $where)
    {
        if($bill->wallet != 'local')
            return null;

        $cond = [
            'user'     => $bill->user,
            'currency' => $bill->currency
        ];

        if(isset($where['type']))
            $cond['type'] = (int)$where['type'];

        if(isset($where['merchant'])){
            $cond['type'] = 3;
            $cond['merchant'] = (int)$where['merchant'];
        }

        $result = (object)[
            'wallets' => []
        ];

        $wallets = $this->walletsRepository->getWallet();

        $wallets = $wallets->
        map(function ($wallet) use ($bill) {
            $wallet->user = ['id' => $bill->user];
            $wallet->type = [
                'label' => 'E-Money',
                'value' => 1];
            $wallet->balance = (float)$wallet->balance;
            $wallet->ncash = (float)$wallet->ncash;
            $wallet->updated = $wallet->updated_at;
            $wallet->created = $wallet->created_at;
            
            return collect($wallet)->only(['id','user','balance','ncash','type','merchant','created','updated']);
          });
        

        if(!$wallets)
            return null;

        $result->wallets = $wallets;

        return $result;
    }

    public function getInfoCard($bill, $where)
    {
        if ($bill->currency != 'IDR') {
            return null;
        }

        $result = (object) ['cards' => []];

        $total = $bill->amount - $bill->paid;
        if ($total < 10000) {
            return $result;
        }

        $cond = [
            'user' => $bill->user,
            'visibility' => true,
        ];

        if (isset($where['status'])) {
            $cond['status'] = $where['status'];
            if (is_array($cond['status'])) {
                $cond['status'][] = '__!';
            }
        }

        $cards = $this->cardRepository->get($cond);
        if (! $cards) {
            return $result;
        }

        $result->cards = $cards;

        return $result;
    }

    public function getOptionalRequest($request, $queries)
    {
        $result = [];
        foreach ($queries as $query) {
            if (isset($request[$query])) {
                $result[$query] = $request[$query];
            }
        }

        return $result;
    }

    public function addPayment($billId, Request $request)
    {
        $bill = $this->checkBill($billId);
        if (! $bill)
            return $this->response(404, []);

        //warning check otp
        $site = $this->siteParamsRepository->getFirst(['name' => 'otp_email_purchase_amount', 'type' => 1]);
        if (isset($site->value) && $site->value < $request->data['amount']) {
            if (!isset($request->data['otp']))
                return $this->response(422, [], trans('messages.otp-invalid'));

            $otp = validate_otp(auth('api')->user()->username, 'payment', $request->data['otp']);

            if (is_null($otp))
                return $this->response(422, [], trans('messages.otp-invalid'));

            if($otp->isExpired())
                return $this->response(422, [], trans('messages.otp-expired'));
        }

        $where = ['bill' => $bill->id, 'method' => $request->method];
        $payments = $this->billPaymentRepository->get($where);
        if (count($payments) != 0) {
            return $this->response(422, ['method' => 'This payment method already used']);
        }

        $method = $request->method;

        if ($method == 'nusaku')
            $result = $this->createPaymentNusaku($bill, (object)$request->data);
        else if ($method == 'card')
            $result = $this->createPaymentCard($bill, (object)$request->data);
            
        if (!isset($result->status)) // error
            return $result;
        

        // let make payment data
        $payment = [
            'bill' => $bill->id,
            'status' => $result->status,
            'method' => $request->method,
            'internal' => 0,
            'data' => json_encode(['create' => $result->data]),
            'amount' => $result->amount,
            'object' => $result->object,
        ];

        $payment = $this->billPaymentRepository->insert($payment);
        if ($result->status == 2) {
            $bill_set = [
                'paid' => $bill->paid + $result->amount,
                'status' => 2,
            ];

            if ($bill->paid + $result->amount == $bill->amount) {
                $bill_set['status'] = 3;
            }

            $where = ['id' => $bill->id];
            $payments = $this->billRepository->update($where, $bill_set);
        }

        // jojon
        $digitalTrans = $this->ppobRepository->getDetailByOrder_id($bill->invoice);
        $digitalProduct = $this->ppobRepository->getProduct($digitalTrans->code);

        $inq = $digitalTrans->meta;
        if (!is_array($inq))
            $inq = json_decode($inq);

        $inquirys = $inq->inquiry; 
        \Log::info('Cellular-add-order :: check meta DTPPOB aman');
        if(!isset($inquirys)){
            return response()->json([
                'success' => false,
                'response_code' => 422,
                'message' => 'This payment product service inquiry not found',
            ], 422);
        }
        $user = auth('api')->user();
        $userWallet = $this->walletsRepository->getWalletByUser($user->id);
        $userToken = $this->usersRepository->getAccessTokens($user);
        $WB = $this->walletsRepository->checkBalance();
        $total = (double)$digitalProduct->admin_fee + (double)$digitalProduct->price;
        
        if($WB < $total) {
            return response()->json([
                "success" => false,
                "response_code" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "message" => trans('messages.wallet.transfer.insufficient-balance'),
                "data" => []
            ]);
        }

        $params = (object)['product_id' => $digitalTrans->code, 'customer_phone' => $digitalTrans->phone, 'reff_number' => $digitalTrans->order_id];

        // Call PPOB inquery 
        $repo = \App::make($inquirys);
        $res = $repo->inquiry($params, $user);
        
        // $test = $this->cellularRepository->inquiry($params, $user);
        \Log::info('Cellular-add-order :: Update DTPPOB aman');

        // insert di transaksi wallet db : wallet.wallet_transactions
        $transactionData = [
            'wallet_id' => $userWallet->id,
            'reff_id' => $digitalTrans->order_id,
            'amount' => $total,
            'transaction_type' => 'PPOB',
            'status' => 2,
            'label_id' => 1,
            'note' => 'Out',
            'location' => $userToken->location,
            'balance_before' => $userWallet->balance,
            'created_at' => now(),
            'updated_at' => now()
        ];
        
        $walletTransaction = $this->walletsTransactionsRepository->store($transactionData);
        
        \Log::info('Cellular-add-order :: Insert WT aman');

        // update di wallet
        // $amountB = $userWallet->balance - $total;
        $update = $this->walletsRepository->minBalance($userWallet->id,$total);
        // dd($amountB);
        \Log::info('Cellular-add-order :: Update Balance wallet aman');
        // end jojon

        $payment = $this->formater($payment);

        return response()->json(["success" => true,
            "response_code" => 200,
            "message" => "OK",
            "data" => $payment,
            "errors" => null
        ]);

        return $this->response(0, $payment);
    }

    public function formater($data)
    {
        $bill = $this->billRepository->get(['id' => $data->bill]);
        $data->bill = $bill;

        $data->status = [
            'label' => 'Success',
            'value' => 2];

        $data->method = [
            'label' => 'Nusaku',
            'value' => 'nusaku'];
                
        $data->updated = $data->updated_at;
        $data->created = $data->created_at;
        unset($data->created_at, $data->updated_at);

        return $data;
    }

    public function createPaymentCard($bill, $request)
    {
        $where = [
            'user' => $bill->user,
            'id' => $request->card,
        ];

        $card = $this->cardRepository->getFirst($where);
        if (! $card) {
            return $this->response(404, [], 'Card not found');
        }

        if ($request->amount > ($bill->amount - $bill->paid)) {
            return $this->response(422, [], 'Total amount is too much');
        }

        $result = (object) [
            'status' => 1,
            'amount' => $request->amount,
            'data' => $card,
            'object' => $card->id,
        ];

        return $result;
    }

    public function createPaymentNusaku($bill, $request)
    {
        $wallet = $this->walletsRepository->getWalletById($request->wallet);

        if(!$wallet)
            return $this->response(404, [], 'Wallet not found');

        if(!isset($request->ncash))
            $request->ncash = 0;

        $w_total = $request->amount + $request->ncash;
        if($w_total > ( $bill->amount - $bill->paid ))
            return $this->response(422, [], 'Total amount is too much');

        $body = [
            'label'         => 1,
            'currency'      => $bill->currency,
            'invoice'       => $bill->invoice,
            'cart'          => $bill->invoice,
            'password'      => $request->password,
            'amount'        => $request->amount,
            'description'   => $bill->description,
            'reff'          => 'PM-' . $bill->id,
            'ncash'         => $request->ncash,
            'otp'           => $request->otp ?? null
        ];

        if($wallet->merchant)
            $body['merchant'] = $wallet->merchant;

        // if(!($res = LWallet::partialPaymentCommit($body)))
        //     return self::setError(LWallet::lastError());

        $request = (object)[
            'status' => 2,
            'amount' => $w_total,
            'data'   => [],
            'object' => $wallet->id
        ];

        return $request;
    }

    // public function createPaymentPromo($bill, $request)
    // {
    //     if(!isset($request->code))
    //         return $this->response(404, [], "Promotion code is required");

    //     $result = (object)[
    //         'status' => 2,
    //         'amount' => 0,
    //         'data'   => (object)[]
    //     ];

    //     Promo::checkCode($bill->invoice, $request->code) // curl

    //     $result->amount = (int)$promo->value;

    //     if($result->amount > $bill->amount)
    //         $result->amount = $bill->amount;
        
    //     $result->data->check = $promo;
    //     if($result->amount < 1)
    //         return $this->response(404, [], "Promotion code is required");

    //     // 2. check if promotion balance is sufficient
    //     $merchant_id = \Mim::$app->config->npBillPromo->promoMerchantId;
    //     $wallet = Wallet::getOne(['merchant'=>$merchant_id]);
    //     if(!$wallet)
    //         return self::setError(lang('np.bill.promo.error.promotion_wallet_not_found'));
    //     if($wallet->balance < $result->amount)
    //         return self::setError(lang('np.bill.promo.error.insufficient_promotion_wallet'));

    //     // 3. use the code
    //     if(!($usage = Promo::useCode($bill->invoice, $request->code, $bill->description)))
    //         return self::setError(Promo::lastError());

    //     $result->data->use = $usage;

    //     // 4. take fund from promotion wallet
    //     $tr_body = [
    //         'amount'   => $result->amount,
    //         'user'     => $bill->user,
    //         'invoice'  => $bill->invoice,
    //         'merchant' => $bill->merchant,
    //         'currency' => $bill->currency,
    //         'code'     => $request->code
    //     ];
    //     if(!($trans = LWallet::discountCommit($tr_body))){
    //         Promo::cancelCode($bill->invoice, $request->code, LWallet::lastError());
    //         return self::setError(LWallet::lastError());
    //     }

    //     $result->data->commit = $trans->ex_discount;
    //     $result->data->commit->transaction = $trans->id;

    //     $result->object = (object)[
    //         'code'   => $request->code,
    //         'amount' => (int)$promo->value
    //     ];

    //     return $result;
    // }

    public function checkBill($billId = null)
    {
        $where = ['user' => $this->userId];
        if (isset($billId)) {
            $orWhere1 = ['id' => (int)$billId];
            $orWhere2 = ['invoice' => $billId];
            $bill = $this->billRepository->getByOrWhere($where, $orWhere1, $orWhere2);
        } else {
            $bill = $this->billRepository->get($where);
        }

        return $bill;
    }

    private $listmethods = [
        'wallet',
        'promo',
        'card'
    ];

    public function getPaymentMethod ($billId, Request $request)
    {
        $response = init_transaction_data($request);
        $bill = $this->ppobRepository->getTransactionByInvoiceNo($billId);

        if (!isset($bill)) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = trans('error.data_not_found');

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        $result = [];

        foreach ($this->listmethods as $method) {
            $result[$method] = null;

            $res = [
                'name' => $method,
                'content' => null,
            ];

            if ($method == 'promo') { // WARNING
                $res['content'] = ['code' => 'Please provide promotion code'];

            }else if($method == 'card') {
                // $res['content'] = $this->getInfoCard($bill, $where);
                $res['content'] = [];

            }else if($method == 'wallet') {
                $res['content'] = $this->getWallet($bill);
            }

            if ($res['content'])
                $result[$method] = $res;
        }

        $response['response']['success'] = true;
        $response['response']['response_code'] = 200;
        $response['response']['message'] = trans('messages.payment-method-found');
        $response['response']['data'] = $result;

        return Response($response['response'])->header('Content-Type', 'application/json');
    }

    public function getWallet ($bill)
    {
        $result = (object)[
            'wallets' => []
        ];

        $wallets = $this->walletsRepository->getWallet();

        $wallets = $wallets->map(function ($wallet) use ($bill) {
            $wallet->user = ['id' => $bill->user_id];
            $wallet->type = [
                'label' => 'E-Money',
                'value' => 1];
            $wallet->balance = (float)$wallet->balance;
            $wallet->ncash = (float)$wallet->ncash;
            $wallet->updated = $wallet->updated_at;
            $wallet->created = $wallet->created_at;
            
            return collect($wallet)->only(['id','user','balance','ncash','type','created','updated']);
        });
        

        if(!$wallets)
            return null;

        $result->wallets = $wallets;

        return $result;
    }

    public function payment ($billId, Request $request)
    {
        $response = init_transaction_data($request);

        $validator = Validator::make($request->all(), [
            'method' => 'required',
            'data' => 'required'
        ]);

        if ($validator->fails()) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = $validator->messages()->first();

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        $transaction = $this->ppobRepository->getTransactionByInvoiceNo($billId);
        $bill = $this->billRepository->getBill($billId);

        if (!isset($transaction) || !isset($bill)) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = trans('error.data_not_found');

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        if ($transaction->status != 2) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;

            if ($transaction->status == 1) {
                $response['response']['message'] = 'transaction failed';
            } elseif ($transaction->status == 3 || $bill->paid == 1) {
                $response['response']['message'] = 'bill has been paid';
            }

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        if ($bill->expires < now()) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = trans('messages.transaction-expired');

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        $method = $request->method;

        if ($method == "wallet") {
            $result = $this->createPaymentWallet($transaction, (object) $request->data);
        } elseif ($method == "card") {
            // $result = $this->createPaymentCard($transaction, (object)$request->data);
            $result = null;
        }

        if (!$result['status']) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = $result['message'];

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        $paymentMethodInfo = $result['data'];

        $data = [
            'payment_method' => $method,
            'payment_data' => json_encode($result['payment_data']),
            'reff_method_id' => $paymentMethodInfo->id
        ];

        $condition = ['invoice' => $billId];

        $update_transaction = $this->billRepository->update($condition, $data);

        // tembak ke function masing" service
        $repo = App::make($bill->payment_service);
        $res = $repo->payment($billId);

        if (!$res['status']) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = $res['message'];

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        $transactionInfo = null;

        if ($method == 'wallet') {
            $transactionInfo = $this->walletsTransactionsRepository->addTransactionAndUpdateBalance($transaction, $paymentMethodInfo);
        } elseif ($method == 'card') {
            // call card repo
        }
        
        if (!isset($transactionInfo)) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = trans('error.payment_failed');

            return Response($response['response'])->header('Content-Type', 'application/json');
        } 
        
        if (!$transactionInfo['status']) {
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            $response['response']['message'] = $transactionInfo['message'];

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        // update transaction status and bill status
        $this->updateBillAndTransactionStatus(3, $billId);

        $response['response']['success'] = true;
        $response['response']['response_code'] = 200;
        $response['response']['message'] = 'success';
        $response['response']['data'] = $res['data'];

        return Response($response['response'])->header('Content-Type', 'application/json');
    }

    private function createPaymentWallet($bill, $request)
    {
        $wallet = $this->walletsRepository->getWalletById($request->wallet);

        if (!isset($wallet)) {
            return [
                'status' => false,
                'message' => trans('messages.wallet-not-found')
            ];
        }

        if ( $wallet->balance < $bill->total ) {
            return [
                'status' => false,
                'message' => trans('messages.insufficient-wallet-balance')
            ];
        }

        return [
            'status' => true,
            'message' => 'ok',
            'payment_data' => [],
            'data' => $wallet
        ];
    }

    private function updateBillAndTransactionStatus ($status, $invoice_no) {
        $bill = [
            'paid' => 0,
            'status' => 2
        ];
        $transaction = ['status' => 2];

        if ($status == 3) {
            $bill = [
                'paid' => 1,
                'status' => 3
            ];
            $transaction = ['status' => 3];
        } elseif ($status == 1) {
            $transaction = ['status' => 1];
        }

        $updateBill = $this->billRepository->update(['invoice' => $invoice_no], $bill);
        $updateTrans = $this->ppobRepository->updateTransactionByInvoice( $invoice_no, $transaction);
    }
}
