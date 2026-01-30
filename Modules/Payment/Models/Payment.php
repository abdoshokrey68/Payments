<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Order\Models\Order;
use Modules\Payment\Enums\PaymentMethodsEnum;
use Modules\Payment\Enums\PaymentStatusEnum;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'status', 'payment_method', 'amount'];

    protected $casts = [
        'status' => PaymentStatusEnum::class,
        'payment_method' => PaymentMethodsEnum::class,
        'amount' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
