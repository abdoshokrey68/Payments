<?php

namespace Modules\Payment\Gateways;

use Modules\Payment\Interfaces\PaymentGatewayInterface;

class CreditCardGateway implements PaymentGatewayInterface
{
    public function process($order_id, $amount): bool
    {
        try {
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
