<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Models\Wallet\Wallets;
use App\Repositories\Wallet\WalletLimitRepository;
use Illuminate\Http\Request;
use DB;
use App\Resources\Wallet\Wallets\Resource as ResultResource;
use App\Resources\Wallet\Wallets\Collection as Resultcollection;
use App\Repositories\Wallet\WalletsRepository;
use App\Repositories\Wallet\WalletsTransactionsRepository;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $walletRepository;
    private $walletTransactionRepository;
    private $walletLimitRepository;

    public function __construct(
        WalletsRepository $walletsRepository,
        WalletsTransactionsRepository $walletsTransactionsRepository,
        WalletLimitRepository $walletLimitRepository
    ) {
        $this->walletsRepository = $walletsRepository;
        $this->walletTransactionRepository = $walletsTransactionsRepository;
        $this->walletLimitRepository = $walletLimitRepository;
    }

    public function index(Request $request)
    {
        $data = $this->walletsRepository->getWallet($request->all());      

        return new Resultcollection($data);
    }

    public function getById(Request $request, $id)
    {
        $data = $this->walletsRepository->getWalletById($id);
        
        return response()->json([
            'success' => TRUE,
            'response_code' => 200,
            'data' => !$data ? [] : new ResultResource($data),
        ], 200);
    }

    public function getWalletLimit(Request $request, $id)
    {
        $data = $this->walletsRepository->getWalletLimit($id);

        $response['response']['success'] = true;
        $response['response']['response_code'] = 200;
        $response['response']['message'] = '';
        $response['response']['data'] = $data;
        
        return $response['response'];
    }

    public function getTransaction(Request $request, $id)
    {
        $response = init_transaction_data($request, null);
        $page = $request->input('page');
        $is_spending = $request->input('spending');
        $data = $this->walletTransactionRepository->getListTransaction($id, $page, $is_spending);

        $response['response']['success'] = true;
        $response['response']['response_code'] = 200;
        $response['response']['message'] = trans('messages.transaction-found');
        $response['response']['data'] = $data ?? [];
        
        return Response($response['response'])->header('Content-Type', 'application/json');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function validateTransaction(Request $request, $id)
    {
        $response = init_transaction_data($request);
        $user = $request->user();

        $wallet = $this->walletsRepository->getWalletById($id);

        if (!$wallet) {
            $response['response']['message'] = trans('messages.wallet-not-found');
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;
            
            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        $amount = $request->input('amount');
        $ncash   = $request->input('ncash');
        $spending = $request->input('spending');

        if ($amount <= 0) {
            $response['response']['message'] = trans('messages.invalid-value', ['data' => 'Amount']);
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        if ($ncash < 0) {
            $response['response']['message'] = trans('messages.invalid-value', ['data' => 'ncash']);
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        if ($wallet->lock_out) {
            $response['response']['message'] = trans('messages.wallet.transfer.lock-out');
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        if ($wallet->lock_tf) {
            $response['response']['message'] = trans('messages.wallet.transfer.lock-tf');
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        if ($spending == 1) {
            if ($amount > ($wallet->balance - $wallet->hold) + $wallet->ncash) {
                $response['response']['message'] = trans('messages.wallet.transfer.insufficient-balance');
                $response['response']['success'] = false;
                $response['response']['response_code'] = 422;

                return Response($response['response'])->header('Content-Type', 'application/json');
            }
            if ($ncash > $wallet->ncash) {
                $response['response']['message'] = trans('messages.insufficient-ncash-balance');
                $response['response']['success'] = false;
                $response['response']['response_code'] = 422;

                return Response($response['response'])->header('Content-Type', 'application/json');
            }
        }

        // check wallet limit for transaction
        $filter = [
            'transfer_daily',
            'transaction_monthly'
        ];
        $walletLimit = $this->walletLimitRepository->getLimitWallet($id, $filter);
        $sumOfDailyTransfer = $this->walletLimitRepository->checkLimitTransferDaily($id);
        $limitRemain = $walletLimit->transfer_daily - $sumOfDailyTransfer;

        if ($amount > $limitRemain) {
            $response['response']['message'] = trans('messages.wallet.transfer.daily-limit', ['limit' => $limitRemain]);
            $response['response']['success'] = false;
            $response['response']['response_code'] = 422;

            return Response($response['response'])->header('Content-Type', 'application/json');
        }

        $response['response']['message'] = 'success';

        return Response($response['response'])->header('Content-Type', 'application/json');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wallet\Wallets  $wallets
     * @return \Illuminate\Http\Response
     */
    public function show(Wallets $wallets)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wallet\Wallets  $wallets
     * @return \Illuminate\Http\Response
     */
    public function edit(Wallets $wallets)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wallet\Wallets  $wallets
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wallets $wallets)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wallet\Wallets  $wallets
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wallets $wallets)
    {
        //
    }
}
