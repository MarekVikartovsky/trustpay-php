<?php

namespace MarekVikartovsky\TrustPay\Exceptions;

use Exception;
use Throwable;

class TrustPayPaymentRequestException extends Exception
{
    /**
     * @param array $data
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(private readonly array $data = [], int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('TrustPay payment request failed!', $code, $previous);
    }

    /**
     * Returns response data.
     *
     * @return array
     */
    public function getResponseData(): array
    {
        return $this->data;
    }
}