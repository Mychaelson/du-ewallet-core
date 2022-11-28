<?php

namespace App\Http\Controllers\Promotions;

use App\Http\Controllers\Controller;
use App\Models\Promotions\StampCatalogue as Catalogue;
use App\Resources\Promotions\MerchantStamp\Collection as MerchantStampCollection;

class PromotionController extends Controller
{

    public function merchant($id)
    {

        $product = Catalogue::select('stamp_catalogue.*')->where('merchant',$id)
                    ->paginate();

        return new MerchantStampCollection($product);
    }

}
