<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Resources\Wallet\Wallets\Resource as ResultResource;
use App\Resources\Wallet\Wallets\Collection as Resultcollection;
use App\Repositories\Wallet\WalletsRepository;

class WalletLabelsController extends Controller
{
    private $walletRepository;

    public function __construct(
        WalletsRepository $walletsRepository,
    ) {
        $this->walletsRepository = $walletsRepository;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $this->walletsRepository->getLabel($request->all());      

        return new Resultcollection($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Models\Wallet\WalletLabels  $walletLabels
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $data = $this->walletsRepository->getLabelById($id);      

        return new Resultcollection($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wallet\WalletLabels  $walletLabels
     * @return \Illuminate\Http\Response
     */
    public function edit(WalletLabels $walletLabels)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wallet\WalletLabels  $walletLabels
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WalletLabels $walletLabels)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wallet\WalletLabels  $walletLabels
     * @return \Illuminate\Http\Response
     */
    public function destroy(WalletLabels $walletLabels)
    {
        //
    }
}
