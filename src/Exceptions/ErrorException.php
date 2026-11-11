<?php

namespace Sendio\Exceptions;

use Exception;

class ErrorException extends Exception
{
    /** 402: the credit balance does not cover the send. Nothing was sent. */
    public const INSUFFICIENT_CREDITS = 'insufficient_credits';

    /** 429: too many requests per second. */
    public const RATE_LIMIT_EXCEEDED = 'rate_limit_exceeded';

    /** 429: the account's daily sending limit. */
    public const DAILY_QUOTA_EXCEEDED = 'daily_quota_exceeded';

    /** 429: the account's monthly sending limit. */
    public const MONTHLY_QUOTA_EXCEEDED = 'monthly_quota_exceeded';

    /** 429: a subaccount's own daily sending limit. */
    public const SUBACCOUNT_DAILY_QUOTA_EXCEEDED = 'subaccount_daily_quota_exceeded';

    /** 429: a subaccount's own monthly sending limit. */
    public const SUBACCOUNT_MONTHLY_QUOTA_EXCEEDED = 'subaccount_monthly_quota_exceeded';

    /**
     * Creates a new Error Exception instance.
     */
    public function __construct(private readonly array $contents)
    {
        parent::__construct($contents['message']);
    }

    /**
     * Get the error message.
     */
    public function getErrorMessage(): string
    {
        return $this->getMessage();
    }

    /**
     * Get the error type
     */
    public function getErrorType(): string
    {
        return $this->contents['type'] ?? $this->contents['name'];
    }

    /**
     * Get the error code.
     */
    public function getErrorCode(): int
    {
        return $this->contents['code'] ?? $this->contents['statusCode'];
    }
}
