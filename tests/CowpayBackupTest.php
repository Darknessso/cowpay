<?php

namespace Esameisa\Cowpay\Tests;

use Esameisa\Cowpay\Strategy\Fawry;
// use Tests\TestCase;

// use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\TestCase;

class CowpayBackupTest extends TestCase
{
    public function testBasicBackupTest()
    {
        $base_url = 'https://cowpay.me/api/v0/';
        $merchant_code = 'EYijTNbr2mLu';
        $m_hash_key = '$2y$10$xwnhaSJCInX8YdeDoJBZxOZNYk.9lhls7RCU4ipxdqaMfxsXQS/.m';
        $currency = 'EGP';

        $payment = new Fawry($base_url, $merchant_code, $m_hash_key, $currency);

        $order_id = rand(1, 2147483647);
        $user_id = rand(1, 2147483647);
        $amount = rand(1, 1000);

        $payment->setInitParameters($order_id, $user_id, $amount);

        $testCustomerData = collect(
            [
                'customer_name'     => 'Esam Eisa',
                'customer_mobile'   => '01098950608',
                'customer_email'    => 'esameisa12345@gmail.com',
                'description'       => 'test package',
                'charge_items'      => collect(
                    [
                        'itemId'        => '897fa8e81be26df25db592e81c31c',
                        'description'   => 'asdasd',
                        'price'         => '25.00',
                        'quantity'      => '1',
                    ]
                ),

                'card_number'   => '4005550000000001',
                'expiry_year'   => '21',
                'expiry_month'  => '05',
                'cvv'           => '123',
                'save_card'     => '0',
            ]
        );

        $response = $payment->pay($testCustomerData);

        $this->assertIsObject($response);

        $this->assertEquals($order_id, $response->merchant_reference_id);
    }
}
