<?php

namespace Modules\Payment\Enums;

enum PaymentMethodsEnum : int {
    case CREDIT_CARD = 1;
    case PAYPAL = 2;

    public function label(): string
    {
        return match ($this) {
            self::CREDIT_CARD => 'Credit Card',
            self::PAYPAL => 'Paypal',
        };
    }

    public function key(): string
    {
        return match ($this) {
            self::CREDIT_CARD => 'CREDIT_CARD',
            self::PAYPAL => 'PAYPAL',
        };
    }

    public function getKeys(): array {
        return array_column(self::cases(), 'key');
    }
}
