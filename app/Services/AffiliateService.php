<?php

namespace App\Services;

use App\Exceptions\AffiliateCreateException;
use App\Mail\AffiliateCreated;
use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AffiliateService
{
    public function __construct(
        protected ApiService $apiService
    )
    {
    }

    /**
     * Create a new affiliate for the merchant with the given commission rate.
     *
     * @param Merchant $merchant
     * @param string $email
     * @param string $name
     * @param float $commissionRate
     * @return Affiliate
     */
    public function register(Merchant $merchant, string $email, string $name, float $commissionRate): Affiliate
    {
        [$code] = $this->apiService->createDiscountCode($merchant);

        $affiliate = Affiliate::whereRelation('user', 'email', $email)->first();

        if (!$affiliate) {
            $affiliate = Affiliate::create([
                'merchant_id' => $merchant->user_id,
                'commission_rate' => $commissionRate,
                'discount_code' => $code
            ]);
        }
        return $affiliate;
    }
}
