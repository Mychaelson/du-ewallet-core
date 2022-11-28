<?php

namespace App\Http\Controllers\Ppob;

use App\Http\Controllers\Controller;
use App\Models\Ppob\DigitalTransactions;
use App\Repositories\Ppob\Service\ServiceRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Repositories\Wallet\WalletsTransactionsRepository;
use App\Repositories\Ppob\Base\PpobRepository;
use App\Repositories\Wallet\WalletsRepository;

class ServiceController extends Controller
{

    public function __construct(
        private ServiceRepository $serviceRepository, 
        private PpobRepository $ppobRepository, 
        private WalletsRepository $walletsRepository, 
        private WalletsTransactionsRepository $walletsTransactionsRepository)
    {
        
        
    }

    public function userTransaction(Request $request)
    {
        $id = auth('api')->id();
        $data = $this->serviceRepository->getTrans($request, $id);

        return $data;
    }

    public function callbackPortalPulsa(Request $request)
    {
        \Log::info('Cellular-callback-potalpulsa :: getRequest '.json_encode($request->all()));
        $data = $request->content;
        if (!is_array($data))
            $data = json_decode($request->content);

        $statuslist = [
            2 => 'failed',
            3 => 'refund',
            4 => 'success',
        ];
        
        $trans = $this->ppobRepository->getDetailByOrder_id($data->trxid_api);
        \Log::info('Cellular-callback-potalpulsa :: getDB');
        if ($trans) {
            
                $result = [
                    'result' => $trans->result,
                    'serial_number' => isset($data->sn) ? $data->sn : '',
                    'note' => isset($data->note) ? $data->note : '',
                ];
    
                $params_DT = [
                    'response_data' => (array) $data,
                    'status' => $statuslist[$data->status],
                    'result' => json_encode($result),
                    'base_price' => $data->price ?? $trans->base_price,
                ];
                $update_DT = $this->ppobRepository->updateDigitalTransaksi($data->trxid_api,$params_DT);
                \Log::info('Cellular-callback-potalpulsa :: update DBdigital transaksi');
    
                $params_WT = [
                    'status' => $data->status == 4 ? 3 : 1,
                ];
                
                $update_status = $this->walletsTransactionsRepository->updateByReff_id($trans->order_id,$params_WT);
                \Log::info('Cellular-callback-potalpulsa :: update DB wallet transaksi');

                
                if($data->status != 4){
		            $WT = $this->walletsTransactionsRepository->getTransaction(['reff_id' => $trans->order_id]);
                    // $transactionData = [
                    //     'wallet_id' => $WT->wallet_id,
                    //     'reff_id' => 'refund-'.$trans->order_id,
                    //     'amount' => $WT->amount,
                    //     'transaction_type' => 'PPOB Refund',
                    //     'status' => 3,
                    //     'note' => 'In',
                    //     'location' => $WT->location,
                    //     'balance_before' => $WT->balance_before,
                    //     'created_at' => now(),
                    //     'updated_at' => now()
                    // ];
                    
                    // $walletTransaction = $this->walletsTransactionsRepository->store($transactionData);
                    // \Log::info('Cellular-add-order :: Insert WT Refund aman');
                    
            
                    // update di wallet
                    $update = $this->walletsRepository->addBalance($WT->wallet_id,$WT->amount);
                    \Log::info('Cellular-add-order :: Update Balance wallet aman');
                }
            

        }
    }
}
