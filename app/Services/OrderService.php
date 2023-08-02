<?php

namespace App\Services;

use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        protected AffiliateService $affiliateService
    )
    {
    }

    /**
     * Process an order and log any commissions.
     * This should create a new affiliate if the customer_email is not already associated with one.
     * This method should also ignore duplicates based on order_id.
     *
     * @param array{order_id: string, subtotal_price: float, merchant_domain: string, discount_code: string, customer_email: string, customer_name: string} $data
     * @return void
     */
    public function processOrder(array $data): void
    {
        $orderExists = Order::where('id', $data['order_id'])->exists();

        if (!$orderExists) {
            Order::updateOrCreate(['id' => $data['order_id']], $data);
        }

        $this->affiliateService->register($data['merchant_domain'], $data['customer_email'], $data['customer_name'], 0.4);

    }
}
