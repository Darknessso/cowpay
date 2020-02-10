<?php

namespace Esameisa\Cowpay\Strategy;

use Esameisa\Cowpay\Enums\PaymentMethod;
use Esameisa\Cowpay\Interfaces\CowpayStrategyInterface;
use Exception;

class Fawry extends Cowpay implements CowpayStrategyInterface
{
    /**
     * Call parent __construct and set payment method as PaymentMethod::PAYATFAWRY.
     */
    public function __construct($base_url, $merchant_code, $m_hash_key, $currency)
    {
        parent::__construct($base_url, $merchant_code, $m_hash_key, $currency);

        Cowpay::setPaymentMethod(PaymentMethod::PAYATFAWRY);
    }

    /**
     * Pay.
     *
     * @return fawry response
     */
    public function pay($customer)
    {
        try {
            $client = new \GuzzleHttp\Client();

            $apiRequest = $client->request(
                'POST',
                Cowpay::generateApiUrl('fawry/charge-request'),
                [
                    'form_params' => $this->customerData($customer),
                ],
            );

            $response = json_decode($apiRequest->getBody()->getContents(), false);

            return $response;
        } catch (Exception $ex) {
            abort(403, $ex->getMessage());
        }
    }

    /**
     * User data.
     *
     * @return customer data
     */
    public function customerData($customer)
    {
        return Cowpay::basicCustomerData($customer);
    }
}
