<?php

namespace MarekVikartovsky\TrustPay\PaymentMethods;


class Sofort extends PaymentMethod
{
    /**
     * Payment method name.
     *
     * @var string
     */
    public const PAYMENT_METHOD_NAME = 'Sofort';

    /**
     * Prepares request data.
     *
     * @return array
     */
    public function prepareRequestBody(): array
    {
        return [
            'PaymentMethod' => self::PAYMENT_METHOD_NAME,
            'MerchantIdentification' => [
                'ProjectId' => $this->trustPay->projectID,
            ],
            'PaymentInformation' => [
                'Amount' => [
                    'Amount' => $this->payment->getAmount(),
                    'Currency' => $this->payment->getCurrency()->name,
                ],
                'Localization' => $this->trustPay->language,
                'References' => [
                    'MerchantReference' => $this->payment->getReference(),
                ],
                'IsRedirect' => $this->payment->isRedirectAllowed(),
            ],
            'CallbackUrls' => $this->callbackUrls(),
        ];
    }
}