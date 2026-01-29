<?php

namespace App;

enum ErrorResponseEnum
{
    case UNAUTHENTICATED;
    case INVALID_OTP;
    case INVALID_CREDENTIALS;
    case UNAUTHORIZED;
    case EXPIRED;
    case INVALID_ID;
    case NOT_SUBSCRIBED;
    case Blocked;
    case NOT_ACTIVE;
    case INVALID_PHONE;
    case INVALID_GIFT;
    case INVALID_ACTION;
    case INVALID_PARAMETER;
    case NOT_FOUND;
    case ALREADY_EXIST;
    case NOT_ACCEPTABLE;
    case INSUFFICIENT_WALLET_BALANCE;

    public function message(): string
    {
        return match ($this) {
            self::UNAUTHENTICATED => 'Unauthenticated.',
            self::INVALID_OTP => 'Invalid or expired OTP.',
            self::INVALID_CREDENTIALS => 'Invalid credentials.',
            self::UNAUTHORIZED => 'Unauthorized.',
            self::EXPIRED => 'Expired.',
            self::INVALID_ID => 'Invalid ID.',
            self::NOT_SUBSCRIBED => 'Not subscribed.',
            self::Blocked => 'Account blocked.',
            self::NOT_ACTIVE => 'Account not active.',
            self::INVALID_PHONE => 'Invalid phone number.',
            self::INVALID_GIFT => 'Invalid gift.',
            self::INVALID_ACTION => 'Invalid action.',
            self::INVALID_PARAMETER => 'Invalid parameter.',
            self::NOT_FOUND => 'Resource not found.',
            self::ALREADY_EXIST => 'Already exists.',
            self::NOT_ACCEPTABLE => 'Action not acceptable.',
            self::INSUFFICIENT_WALLET_BALANCE => 'Insufficient wallet balance.',
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
            self::EXPIRED,
            self::INVALID_ID,
            self::NOT_SUBSCRIBED,
            self::Blocked,
            self::NOT_ACTIVE,
            self::INVALID_PHONE,
            self::INVALID_GIFT,
            self::INVALID_ACTION,
            self::INVALID_PARAMETER,
            self::ALREADY_EXIST,
            self::INSUFFICIENT_WALLET_BALANCE => 400,
        };
    }
}
