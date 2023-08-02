<?php

namespace App\Services;

use App\Mail\AffiliateCreated;
use App\Models\Affiliate;
use App\Models\Merchant;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * You don't need to do anything here. This is just to help
 */
class ApiService
{
    /**
     * Create a new discount code for an affiliate
     *
     * @param Merchant $merchant
     *
     * @return array{id: int, code: string}
     */
    public function createDiscountCode(Merchant $merchant): array
    {
        return [
            'id' => rand(0, 100000),
            'code' => Str::uuid()
        ];
    }

    /**
     * Send a payout to an email
     *
     * @param string $email
     * @param float $amount
     * @return void
     * @throws RuntimeException
     */
    public function sendPayout(string $email, float $amount)
    {
        try {
            $affiliate = Affiliate::whereRelation('user', 'email', $email)->first();
            Mail::to($email)->send(new AffiliateCreated($affiliate));
        } catch (Exception $e) {
            // we can also show the exception error like $e->getMessage()
            throw new \RuntimeException("Operation Failed ");
        }
    }
}
