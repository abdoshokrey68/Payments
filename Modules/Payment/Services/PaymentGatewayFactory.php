<?php

namespace Modules\Payment\Services;

use Illuminate\Contracts\Container\Container;
use Modules\Payment\Interfaces\PaymentGatewayInterface;

class PaymentGatewayFactory
{

    public function __construct(
        protected Container $container
    ) {}

    public function getGateway(int $paymentMethod): PaymentGatewayInterface
    {
        $key = 'payment.gateway.' . $paymentMethod;

        if (! $this->container->bound($key)) {
            throw new \InvalidArgumentException("Unsupported payment method: {$paymentMethod}");
        }

        return $this->container->make($key);
    }
}
