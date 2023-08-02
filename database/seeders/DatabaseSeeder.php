<?php

namespace Database\Seeders;

use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $merchants = User::factory(2)->create();
        $affiliates = User::factory(2)->affiliate()->create();

        $users = $merchants->merge($affiliates);

        foreach ($users as $user) {
            $merchant = Merchant::factory()
                ->for($user)
                ->create();

            $affiliate = Affiliate::factory()
                ->for($merchant)
                ->for($user)
                ->create();

            $orders = Order::factory()
                ->for($merchant)
                ->for($affiliate)
                ->count(10)
                ->create();

            $paidOrders = $orders->random();

            $paidOrders->update([
                'payout_status' => Order::STATUS_PAID
            ]);
        }

    }
}
