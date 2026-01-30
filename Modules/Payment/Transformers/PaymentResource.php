<?php

namespace Modules\Payment\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\Transformers\OrderResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'status' => [
                'value' => $this->status?->value,
                'label' => $this->status?->label(),
            ] ?? null,
            'payment_method' => [
                'value' => $this->payment_method?->value,
                'label' => $this->payment_method?->label(),
            ] ?? null,
            'amount' => (float) $this->amount,
            'order' => new OrderResource($this->whenLoaded('order')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
