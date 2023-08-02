<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Services\MerchantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MerchantController extends Controller
{
    public function __construct(
        MerchantService $merchantService
    )
    {
    }

    /**
     * Useful order statistics for the merchant API.
     *
     * @param Request $request Will include a from and to date
     * @return JsonResponse Should be in the form {count: total number of orders in range, commission_owed: amount of unpaid commissions for orders with an affiliate, revenue: sum order subtotals}
     */
    public function orderStats(Request $request): JsonResponse
    {

        $from = Carbon::parse($request->from)->subDay();
        $to = Carbon::parse($request->to);
        // suppose we are getting (from and to ) from request
        // $from = Carbon::parse($request->from)->subDay();
        // $to = Carbon::parse($request->to)->addDay();

        $from = now()->subDay();
        $to = now()->addDay();

        $orders = Order::query()
            ->whereBetween('created_at', [$from, $to]);

        $unpaidOrdersCommissionOwed = $orders
            ->whereHas('affiliate')
            ->where('payout_status', Order::STATUS_UNPAID)
            ->sum('commission_owed');


        return response()->json(['count' => $orders->count(), 'commission_owed' => $unpaidOrdersCommissionOwed, 'revenue' => $orders->sum('subtotal')]);
    }
}
