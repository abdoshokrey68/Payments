<?php

namespace App;

enum ErrorResponseEnum
{
    case UNAUTHENTICATED;
    case INVALID_OTP;
    case INVALID_CREDENTIALS;
    case UNAUTHORIZED;
    case Blocked;
    case NOT_ACTIVE;
    case INVALID_ACTION;
    case INVALID_PARAMETER;
    case NOT_FOUND;
    case ALREADY_EXIST;
    case NOT_ACCEPTABLE;
    case ORDER_NOT_CONFIRMED;
    case ALREADY_PAID;
    case PAYMENT_FAILED;


    public function message(): string
    {
        return match ($this) {
            self::UNAUTHENTICATED => 'Unauthenticated.',
            self::INVALID_OTP => 'Invalid or expired OTP.',
            self::INVALID_CREDENTIALS => 'Invalid credentials.',
            self::UNAUTHORIZED => 'Unauthorized.',
            self::Blocked => 'Account blocked.',
            self::NOT_ACTIVE => 'Account not active.',
            self::INVALID_ACTION => 'Invalid action.',
            self::INVALID_PARAMETER => 'Invalid parameter.',
            self::NOT_FOUND => 'Resource not found.',
            self::ALREADY_EXIST => 'Already exists.',
            self::NOT_ACCEPTABLE => 'Action not acceptable.',
            self::ORDER_NOT_CONFIRMED => 'Order not confirmed.',
            self::ALREADY_PAID => 'Already Paid.',
            self::PAYMENT_FAILED => 'Payment failed.',
        };
    }

    public function statusCode(): int
    {
        return match ($this) {
            self::UNAUTHENTICATED => 401,
            self::INVALID_CREDENTIALS => 401,
            self::UNAUTHORIZED => 403,
            self::NOT_FOUND => 404,
            self::NOT_ACCEPTABLE => 406,
            self::INVALID_OTP,
            self::Blocked,
            self::NOT_ACTIVE,
            self::INVALID_ACTION,
            self::INVALID_PARAMETER,
            self::ALREADY_EXIST,
            self::ORDER_NOT_CONFIRMED => 400,
            self::ALREADY_PAID => 400,
            self::PAYMENT_FAILED => 400,
        };
    }
}
