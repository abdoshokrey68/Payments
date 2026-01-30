<?php

namespace Modules\Payment\Interfaces;

interface PaymentGatewayInterface {
    public function process($order_id, $amount): bool;
}
