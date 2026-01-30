<?php

namespace Modules\Payment\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Payment\Models\Payment;

interface PaymentInterface
{

    public function getAllByUser(int $userId, int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Payment;

    public function create(array $data): Payment;

    public function getPaymentByOrderId(int $orderId): ?Payment;
}
