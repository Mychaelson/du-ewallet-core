<?php

namespace App\Repositories\Wallet;


use Illuminate\Support\Facades\DB;
use App\Models\Wallet\Wallets;
use App\Models\Wallet\WalletTransactions;
use App\Repositories\Accounts\UsersRepository;
use Illuminate\Support\Facades\Auth;

class WalletsTransactionsRepository
{

    private $wallet_trans;
    private $walletTransactions;
    private $usersRepository;
    private $walletsRepository;

	function __construct(Wallets $wallet_trans, WalletTransactions $walletTransactions, UsersRepository $usersRepository, WalletsRepository $walletsRepository)
	{
		$this->wallet_trans = $wallet_trans;
        $this->walletTransactions = $walletTransactions;
        $this->usersRepository = $usersRepository;
        $this->walletsRepository = $walletsRepository;
	}
    
    public function store($data){
        $data = WalletTransactions::insertGetId($data);
        return $data;
    }

    public function storeMultiple($data){
        $data = WalletTransactions::insert($data);
        return $data;
    }

    public function getList($filter){
        $data = DB::table('wallet.wallet_transactions');

        $data->when(!empty($filter['wallet']), function ($q) use ($filter) {
            $q->where('wallet_id', '=', $filter['wallet']);
        });

        $data->when(!empty($filter['transaction_type']), function ($q) use ($filter) {
            $q->where('transaction_type', '=', $filter['transaction_type']);
        });

        $data->when(!empty($filter['note']), function ($q) use ($filter) {
            $q->where('note', '=', $filter['note']);
        });

        $data->when(!empty($filter['status']), function ($q) use ($filter) {
            $q->where('status', '=', $filter['status']);
        });

        $data->when(!empty($filter['created']), function ($q) use ($filter) {
            $q->whereDate('created_at', $filter['created']);
        });

        return $data->get();
    }
    
    public function update($transactionId, $data){
        $data = WalletTransactions::where('id', $transactionId)->update($data);
        return $data;
    }

    public function updateByReff_id($transactionId, $data){
        $data = WalletTransactions::where('reff_id', $transactionId)->update($data);
        return $data;
    }

    public function getTransaction($filter){
        $data = $this->walletTransactions->where($filter)->first();
        return $data;
    }

    public function getListTransaction($wallet_id, $page, $is_spending = null){

        $subQuery = DB::table('ppob.transaction_v2 AS a')
                        ->join('ppob.product_v2 AS b', 'a.product_code', '=', 'b.code')
                        ->selectRaw(
                            'a.id, a.invoice_no as order_id, b."name" as description, a.price_sell net_amount, a.admin_fee fee, a.discount, a.total, a.created_at'
                        )
        ;

        $data = DB::table('wallet.wallet_transactions AS a')
                    ->join('wallet.wallet_labels AS b', 'a.label_id', '=', 'b.id')
                    ->leftJoin('payment.pga_bills AS c', function($join){
                        $join->on('a.id', '=', 'c.transaction_id');
                        $join->on('c.action', '=', DB::raw("'Topup'"));
                        $join->whereNotNull('c.invoice_no');
                    })
                    ->leftJoinSub($subQuery, 'd', function($join){
                        $join->on('a.reff_id', '=', 'd.order_id');
                        $join->on('a.transaction_type', '=', DB::raw("'PPOB'"));
                    })
                    ->leftJoin('wallet.wallet_withdraw AS e', function($join){
                        $join->on('a.reff_id', '=', DB::raw("CAST ( e.id AS varchar )"));
                        $join->on('a.transaction_type', '=', DB::raw("'Withdraw'"));
                    })
                    ->leftJoin('wallet.wallet_transfers AS f', function($join){
                        $join->on('a.reff_id', '=', DB::raw("CAST ( f.id AS varchar )"));
                        $join->on('a.transaction_type', '=', DB::raw("'Transfer'"));
                    })
                    ->leftJoin('accounts.users AS g', function($join){
                        $join->on('g.id', '=', DB::raw("case 
                            when a.note = 'In' then f.from
                            when a.note = 'Out' then f.to
                            end"
                        ));
                    })
                    ->where('a.wallet_id', $wallet_id)
                    ->whereRaw("
                        (case when a.transaction_type ='Topup' then c.invoice_no is not null else c.invoice_no is null end)
                    ")
                    ->select(
                        'a.id',
                        'a.reff_id',
                        'a.wallet_id',
                        'b.id AS label_id',
                        'b.name AS label_name',
                        'b.icon AS label_icon',
                        'b.background AS label_background',
                        'b.color AS label_color',
                        'b.spending AS label_spending',
                        'b.default AS label_default',
                        'b.organization AS label_organization',
                        'b.spending AS spending',
                        'c.id AS topup_id',
                        'c.invoice_no AS topup_invoice',
                        'c.created_at AS topup_created',
                        'c.payment_method',
                        'd.id AS payment_id',
                        'd.order_id AS payment_invoice',
                        'd.created_at AS payment_created',
                        'a.created_at',
                        'a.updated_at',
                        'a.balance_before',
                        'a.transaction_type',
                        'a.status',
                        'a.note',
                        'e.id AS withdraw_id',
                        'e.created_at AS withdraw_created',
                        'e.bank_name',
                        'e.bank_code',
                        'e.bank_account_name',
                        'e.bank_account_number',
                        'f.id AS transfer_id',
                        'f.message AS transfer_message',
                        'f.to AS transfer_target_id',
                        'f.from AS transfer_from_id',
                        'g.username AS transfer_username',
                        'g.phone AS transfer_phone',
                    )
                    ->selectRaw("
                        (case 
                            when a.transaction_type ='Topup' then c.net_amount 
                            when a.transaction_type ='PPOB' then d.net_amount
                            when a.transaction_type ='Withdraw' then e.amount
                            when a.transaction_type ='Transfer' then f.amount
                        end) AS amount
                    ")
                    ->selectRaw("
                        (case 
                            when a.transaction_type ='Topup' then c.surcharge 
                            when a.transaction_type ='PPOB' then d.fee
                            when a.transaction_type ='Withdraw' then e.pg_fee
                            when a.transaction_type ='Transfer' then 0
                        end) AS fee
                    ")
                    ->selectRaw("
                        (case 
                            when a.transaction_type ='PPOB' then d.discount
                        end) AS discount
                    ")
                    ->selectRaw("
                        (case 
                            when a.transaction_type ='Topup' then c.amount 
                            when a.transaction_type ='PPOB' then d.total
                            when a.transaction_type ='Withdraw' then e.amount + e.pg_fee
                            when a.transaction_type ='Transfer' then f.amount
                        end) AS total
                    ")
                    ->selectRaw("
                        (case when a.transaction_type ='Topup' then c.invoice_no end) AS reff
                    ")
                    ->selectRaw("
                        (case 
                            when a.transaction_type ='Topup' then 3 
                            when a.transaction_type ='PPOB' then 5
                            when a.transaction_type ='Withdraw' then 4
                            when a.transaction_type ='Transfer' and note = 'In' then 1
                            when a.transaction_type ='Transfer' and note = 'Out' then 2
                        end) AS type
                    ")
                    ->selectRaw("
                        (case 
                            when a.transaction_type ='Topup' then 'Topup' 
                            when a.transaction_type ='PPOB' then d.description
                            when a.transaction_type ='Withdraw' then 'Withdraw'
                            when a.transaction_type ='Withdraw' then 'Transfer'
                            when a.transaction_type ='Transfer' and note = 'In' then 'Transfer In'
                            when a.transaction_type ='Transfer' and note = 'Out' then 'Transfer Out'
                        end) AS description
                    ")
                    ->orderByDesc('a.updated_at')
        ;

        if (isset($is_spending)) {
            $data->where('b.spending', $is_spending);
        }

        // dd($data->toSql());

        $data = $data->paginate(10, ['*'], 'page' ,$page);

        $res = [];
        
        foreach ($data as $info) {

            $balance = 0;

            if ($info->status == 3) {
                if ($info->label_spending == 1) {
                    $balance = (double)$info->balance_before - $info->total;
                } else {
                    $balance = (double)$info->balance_before + $info->amount;
                }
            }

            $res[] = array(
                'id'                => $info->id,
                'amount'            => (double)$info->amount,
                'wallet'            => $info->wallet_id,
                'fee'               => (double)$info->fee,
                'discount'          => (double)$info->discount,
                'total'             => (double)$info->total,
                'spending'          => $info->spending == 1 ? true : false,
                'label'             => array(
                    'id'                => $info->label_id,
                    'name'              => $info->label_name,
                    'icon'              => $info->label_icon,
                    'background'        => $info->label_background,
                    'color'             => $info->label_color,
                    'spending'          => $info->label_spending == 1 ? true : false,
                    'default'           => $info->label_default == 1 ? true : false,
                    'organization'      => $info->label_organization == 1 ? true : false,
                ),
                'ncash'             => 0,
                'balance'           => $balance,
                'external'          => 0,
                'description'       => $info->description,
                'reff'              => $info->reff_id,
                'type'              => $info->type,
                'topup'             => $info->topup_id ? array(
                    'id'                => $info->topup_id,
                    'bank'              => $info->payment_method,
                    'invoice'           => $info->topup_invoice,
                    'created_at'        => $info->topup_created,
                ) : null,
                'transfer'          => $info->transfer_id ? ($info->note == 'Out' ? array(
                    'id'                => $info->transfer_id,
                    'message'           => $info->transfer_message,
                    'target'            => $info->transfer_target_id,
                    'username'          => $info->transfer_username,
                    'phone'             => $info->transfer_phone
                ) : array(
                    'id'                => $info->transfer_id,
                    'message'           => $info->transfer_message,
                    'from'              => $info->transfer_from_id,
                    'username'          => $info->transfer_username,
                    'phone'             => $info->transfer_phone
                )) : null,
                'withdraw'          => $info->withdraw_id ? array(
                    'id'                => $info->withdraw_id,
                    'bank'              => $info->bank_name,
                    'account number'    => $info->bank_account_number,
                    'account name'      => $info->bank_account_name
                ) : null,
                'withdraw_agent'    => null,
                'payment'           => $info->payment_id ? array(
                    'id'                => $info->payment_id,
                    'invoice'           => $info->payment_invoice,
                    'created_at'        => $info->payment_created,
                ) : null,
                'payment_adj'       => null,
                'refund'            => null,
                'refund_external'   => null,
                'cashback'          => null,
                'settlement'        => null,
                'va_payment'        => null,
                'ex_ncash'          => null,
                'ex_ncard'          => null,
                'ex_discount'       => null,
                'voucher'           => null,
                'deduction'         => null,
                'switching'         => null,
                'topup_intl'        => null,
                'status'            => (int)$info->status,
                'reason'            => null,
                'updated'           => $info->updated_at,
                'created'           => $info->created_at,
                'id_show'           => $info->reff_id,
            );
        }

        return $res;
    }

    public function addTransactionAndUpdateBalance ($bill, $wallet_info)
    {
        $user = auth('api')->user();
        $userToken = $this->usersRepository->getAccessTokens($user);

        $transactionData = [
            'wallet_id' => $wallet_info->id,
            'reff_id' => $bill->invoice_no,
            'amount' => $bill->total,
            'transaction_type' => 'PPOB',
            'status' => 3,
            'label_id' => 1,
            'note' => 'Out',
            'location' => $userToken->location,
            'balance_before' => $wallet_info->balance,
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::beginTransaction();
        try {
            $transactionInsert = $this->store($transactionData);
            $updateBalance = $this->walletsRepository->minBalance($wallet_info->id, (double) $bill->total);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e->getMessage());
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }

        return [
            'status' => true,
            'message' => "success",
            'data' => $transactionData
        ];
    }
}
