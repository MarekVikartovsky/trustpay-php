<?php

namespace MarekVikartovsky\TrustPay\PaymentMethods;


class Giropay extends PaymentMethod
{
    /**
     * Payment method name.
     *
     * @var string
     */
    public static string $paymentMethodName = 'Giropay';

    /**
     * Prepares request data.
     *
     * @return array
     */
    public function prepareRequestBody(): array
    {
        return [
            'PaymentMethod' => self::$paymentMethodName,
            'MerchantIdentification' => [
                'ProjectId' => $this->trustPay->projectID,
            ],
            'PaymentInformation' => [
                'Amount' => [
                    'Amount' => $this->payment->getAmount(),
                    'Currency' => $this->payment->getCurrency(),
                ],
                'Localization' => $this->trustPay->language,
                'References' => [
                    'MerchantReference' => $this->payment->getReference(),
                ],
            ],
            'CallbackUrls' => $this->callbackUrls(),
        ];
    }
}