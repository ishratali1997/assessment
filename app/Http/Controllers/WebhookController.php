<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AffiliateService;
use App\Services\ApiService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected ApiService   $apiService
    )
    {
    }

    /**
     * Pass the necessary data to the process order method
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $data = [
            'order_id' => Str::uuid(),
            'subtotal_price' => round(rand(100, 999) / 3, 2),
            'merchant_domain' => 'test.com',
            'discount_code' => Str::uuid(),
            'customer_email' => 'abc@gmail.com',
            'customer_name' => 'Test Customer'
        ];


        $this->orderService->processOrder($data);

        return response()->json();
    }
}
