<?php

namespace App\Repositories\ATM;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Request;

class ATMRepository
{

    public function GetATM()
    {
        $data = DB::table('accounts.atm')
        ->get();

        return [
            'success' => true,
            'response_code' => 200,
            'data' => $data
        ];
    }
}
