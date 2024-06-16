<?php

namespace MarekVikartovsky\TrustPay\PaymentMethods\Contracts;

interface PaymentMethodContract
{
    /**
     * Prepares request data.
     *
     * @return array
     */
    public function prepareRequestBody(): array;
}