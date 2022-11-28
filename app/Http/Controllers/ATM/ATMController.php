<?php

namespace App\Http\Controllers\ATM;

use App\Http\Controllers\Controller;
use App\Repositories\ATM\ATMRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ATMController extends Controller
{
    private $ATMRepository;

    public function __construct(
        ATMRepository $ATMRepository
    ) {
        $this->ATMRepository = $ATMRepository;
    }
    public function index()
    {
        $data = $this->ATMRepository->GetATM();

        return $data;
    }
}
