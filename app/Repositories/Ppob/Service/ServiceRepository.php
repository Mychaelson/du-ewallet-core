<?php

namespace App\Repositories\Ppob\Service;

use App\Models\Ppob\DigitalProducts;
use DB;
use App\Resources\Ppob\Data\DataCollection as ResultCollection;

class ServiceRepository
{
    public function getTrans($request,$id)
    {
        $tr = DB::table('accounts.users as u')->leftJoin('ppob.digital_transactions as dt','dt.user_id','=','u.id')->where('u.id',$id);
        
        
        if($request->has('status')){
            $tr = $tr->where('dt.status', $request->status);
        }else{
            $tr = $tr->whereIn('dt.status', ['pending', 'success', 'book', 'paid', 'failed', 'cancel']);
        }

        if($request->has('type')){
            $tr = $tr->where('dt.type', $request->input('type', 0));
        }

        if($request->has('code')){
            $tr = $tr->where('dt.code', $request->code);
        }

        if($request->has('product_id')){
            $product = DigitalProducts::find($request->product_id);
            if($product) {
                $tr = $tr->where('code', $product->code);
            }
        }

        if($request->has('category')) {
            $tr = $tr->where('category', $request->category);
        }

        if($request->has('start_at') || $request->has('end_at')){
        
            $startAt = $request->input('start_at', false);
            $endAt = $request->input('end_at', false);

            if($startAt && ! $endAt){
                $startAt = Carbon::parse($startAt);
                $tr = $tr->whereDate('created_at', $startAt->format('Y-m-d'));
            }elseif(! $startAt && $endAt){
                $startAt = Carbon::parse($endAt);
                $tr = $tr->whereDate('created_at', $startAt->format('Y-m-d'));
            }else {
                $startAt = Carbon::parse($startAt);
                $endAt = Carbon::parse($endAt);
                $tr = $tr->whereBetween('created_at', [$startAt->format('Y-m-d H:i:s'), $endAt->format('Y-m-d H:i:s')]);
            } 
        }

        $total = $tr->sum('total');
        $transactions = $tr->orderBY('dt.created_at','desc')->paginate($request->input('rpp', 10));

        return (new ResultCollection($transactions))->additional(['meta' => [
                    'amount' => $total,
                ]]);


    }
}
