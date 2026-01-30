<?php

namespace Modules\Payment\Enums;

enum PaymentStatusEnum: int {
    case PENDING = 1;
    case SUCCESSFUL = 2;
    case FAILED = 3;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::SUCCESSFUL => 'Successful',
            self::FAILED => 'Failed',
        };
    }

    public function key(): string
    {
        return match ($this) {
            self::PENDING => 'PENDING',
            self::SUCCESSFUL => 'SUCCESSFUL',
            self::FAILED => 'FAILED',
        };
    }

    public function getKeys(): array {
        return array_column(self::cases(), 'key');
    }
}
