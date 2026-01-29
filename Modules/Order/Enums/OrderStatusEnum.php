<?php

namespace Modules\Order\Enums;

enum OrderStatusEnum: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::CONFIRMED => 'Confirmed',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function key(): string
    {
        return match($this) {
            self::PENDING => 'PENDING',
            self::CONFIRMED => 'CONFIRMED',
            self::CANCELLED => 'CANCELLED',
        };
    }

    public function getKeys(): array {
        return array_column(self::cases(), 'key');
    }
}
