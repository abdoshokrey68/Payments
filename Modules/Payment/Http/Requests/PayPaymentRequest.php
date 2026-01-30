<?php

namespace Modules\Payment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Payment\Enums\PaymentMethodsEnum;

class PayPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'payment_method' => [
                'required',
                'integer',
                Rule::in(array_column(PaymentMethodsEnum::cases(), 'value')),
            ],
        ];
    }
}
