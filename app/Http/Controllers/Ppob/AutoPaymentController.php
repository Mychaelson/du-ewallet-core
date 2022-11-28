<?php

namespace App\Http\Controllers\Ppob;

use App\Http\Controllers\Controller;
use App\Repositories\Ppob\DigitalCategoryRepository;
use App\Repositories\Ppob\DigitalProductsRepository;
use App\Repositories\Ppob\PaymentSchedulesRepository;
use App\Repositories\Ppob\SavedNumberRepository;
use App\Repositories\Wallet\WalletsRepository;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class AutoPaymentController extends Controller
{
    public function __construct(
        private PaymentSchedulesRepository $paymentSchedulesRepository,
        private DigitalProductsRepository $digitalProductsRepository,
        private DigitalCategoryRepository $digitalCategoryRepository,
        private SavedNumberRepository $savedNumberRepository,
        private WalletsRepository $walletsRepository,
    ) {
        $this->userId = auth('api')->id();
    }

    public function addSchedule(Request $request)
    {
        $orWhere1 = ['code' => $request->product_id];
        $orWhere2 = ['id' => $request->product_id];
        $product = $this->digitalProductsRepository->orWhere($orWhere1, $orWhere2);

        if (! $product) {
            return $this->response(null, 404, 'Product not found');
        }

        $data = $this->makeWalletHash($request);
        if (! $data) {
            return $this->response(null, 500, 'Wallet Hash Unauthorized');
        }

        $where = [
            'user_id' => $this->userId,
            'customer_id' => $request->customer_id,
            'product_id' => $product->id,
        ];

        $update = [
            'name' => $request->name,
            'payment_at' => $request->payment_at,
            'code' => $product->code,
            'repeat' => $request->repeat ?? 'false',
            'category' => $request->category,
            'wallet_hash' => $data->password,
            'wallet_id' => $data->id,
            'status' => 0,
        ];
        $data = $this->paymentSchedulesRepository->updateOrCreate($where, $update);

        return $this->response($this->paymentScheduledResource($data), 200, 'Added  success');
    }

    public function createHashPassword($request)
    {
        $description = "{$request->name} ({$request->customer_id})";
        if(!isset($request->password))
            return $this->response(['password'=>'this field is required'], 422);

        $spass = sha1(uniqid() . time() . rand(1000,9999));
        $len = strlen($spass);
        for($i=0; $i<$len; $i++){
            $chr = $spass[$i];
            if(rand(1,10) < 5){
                $chr = strtoupper($chr);
                $spass[$i] = $chr;
            }
        }
        
        $id = $this->walletsRepository->createPassword([
            'user' => $this->userId,
            'description' => $description,
            'password' => password_hash($spass, PASSWORD_DEFAULT)
        ]);

        return [
            'id'            => $id,
            'password'      => $spass,
            'description'   => $description];
    }

    private function makeWalletHash($request)
    {
        if ($request->has('password')) {
            $hashPassword = $this->createHashPassword($request);

            return (object)$hashPassword;
        }

        return false;
    }

    public function categories()
    {
        $categories = $this->digitalCategoryRepository->get();

        return $this->response($categories);
    }

    public function productInCategory($id)
    {
        $where = [
            'digital_categories.id' => $id,
            'digital_products.status' => 1,
        ];
        $categories = $this->digitalCategoryRepository->first($where);

        if ($categories) {
            return $this->response($this->categoryProductResource($categories));
        }

        return $this->response(null, 404, 'data not found');
    }

    // WARNING schedule
    public function schedules(Request $request)
    {
        $where = ['payment_schedules.user_id' => $this->userId];
        $data = $this->paymentSchedulesRepository->paginate($where, $request);

        return $this->response($this->paymentScheduledResource(collect($data->items())));
    }

    public function schedule($id)
    {
        $where = [
            'payment_schedules.user_id' => $this->userId,
            'payment_schedules.id' => $id,
        ];
        $data = $this->paymentSchedulesRepository->first($where);

        return $this->response($this->paymentScheduledResource($data));
    }

    public function cancelSchedule($id)
    {
        $where = [
            'user_id' => $this->userId,
            'id' => $id,
        ];
        $update = ['status' => 2];

        $this->paymentSchedulesRepository->update($where, $update);

        return $this->response(null, 200, 'Cancel autopayment');
    }

    // WARNING bookmarks
    public function bookmarks(Request $request)
    {
        $where = ['user_id' => $this->userId];
        $bookmarks = $this->savedNumberRepository->paginate($where, $request);

        return $this->response($bookmarks->items());
    }

    public function removeBookmark($id, Request $request)
    {
        $where = [
            'id' => $id,
            'user_id' => $this->userId,
        ];
        $bookmark = $this->savedNumberRepository->delete($where);

        if ($bookmark) {
            return $this->response(null, 200, 'bookmark remove success');
        }

        return $this->response(null, 404, 'data not found');
    }

    public function addBookmark(Request $request)
    {
        $orWhere1 = ['id' => (int) $request->product_id];
        $orWhere2 = ['code' => $request->product_id];
        $product = $this->digitalProductsRepository->orWhere($orWhere1, $orWhere2);
        if (! $product) {
            return $this->response(null, 404, 'data not found');
        }

        if ($request->category === 'pulsa') {
            $where = [
                'user_id' => $this->userId,
                'customer_id' => $request->customer_id,
            ];

            $update = [
                'code' => $product->code,
                'name' => $product->name,
                'product_id' => $product->id,
                'category' => $request->category,
            ];

            $bookmark = $this->savedNumberRepository->updateOrCreate($where, $update);
        } else {
            $where = [
                'user_id' => $this->userId,
                'code' => $product->code,
                'customer_id' => $request->customer_id,
            ];

            $update = [
                'name' => $product->name,
                'product_id' => $product->id,
                'category' => $request->category,
            ];

            $bookmark = $this->savedNumberRepository->updateOrCreate($where, $update);
        }

        return $this->response($this->bookmarkResource($bookmark));
    }

    // ----------------------------------------------------------------
    public function response($data, $code = 200, $message = null)
    {
        $res = [
            'success' => $code == 200,
            'response_code' => $code,
            'data' => $data ?? [],
            'message' => $message,
        ];

        return response()->json($res, $code);
    }

    public function bookmarkResource($data)
    {
        $icons = config('notif_icon');

        $data->map(function ($q) use ($icons) {
            $q['icon'] = $icons[$q->category] ?? '';

            return $q;
        });

        return $data;
    }

    public function paymentScheduledResource($data)
    {
        $icons = config('notif_icon');

        $data->map(function ($q) use ($icons) {
            $q['icon'] = $icons[$q->category] ?? '';

            return $q;
        });

        return $data;
    }

    public function categoryProductResource($data)
    {
        $data->map(function ($q) {
            $q['is_parent'] = $q->is_parent == 0;
            // $q['products'] = ProductResource::collection($this->whenLoaded('products'));

            return $q;
        });

        return $data;
    }
}
