<?php

namespace MarekVikartovsky\TrustPay\PaymentMethods;

use Illuminate\Http\Client\ConnectionException;
use MarekVikartovsky\TrustPay\Exceptions\TrustPayPaymentRequestException;
use MarekVikartovsky\TrustPay\Payment;
use MarekVikartovsky\TrustPay\PaymentMethods\Contracts\PaymentMethodContract;
use MarekVikartovsky\TrustPay\TrustPay;
use Illuminate\Support\Facades\Http;

abstract class PaymentMethod implements PaymentMethodContract
{
    /**
     * Payment method name.
     *
     * @var string
     */
    public static string $paymentMethodName = '';

    /**
     * Payment request url.
     *
     * @var string
     */
    protected string $paymentRequestUrl = 'https://aapi.trustpay.eu/api/Payments/Payment';

    /**
     * Payment response data.
     *
     * @var array
     */
    private array $paymentResponse = [];

    /**
     * @param TrustPay $trustPay
     * @param Payment $payment
     */
    public function __construct(
        protected TrustPay $trustPay,
        protected Payment $payment
    ) {}

    /**
     * Handle request to obtain gateway url.
     *
     * @return string
     * @throws ConnectionException
     * @throws TrustPayPaymentRequestException
     */
    public function handle(): string
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->payment->accessToken,
        ])->asJson()->post($this->paymentRequestUrl, $this->prepareRequestBody());

        $this->paymentResponse = $response->json();

        return $this->paymentResponse['GatewayUrl'] ?? throw new TrustPayPaymentRequestException($this->paymentResponse);
    }

    /**
     * Returns response data.
     *
     * @return array
     */
    public function getResponseData(): array
    {
        return $this->paymentResponse;
    }

    /**
     * Build callback urls object.
     *
     * @return array
     */
    protected function callbackUrls(): array
    {
        return [
            'Success' => $this->trustPay->returnUrl,
            'Cancel' => $this->trustPay->cancelUrl,
            'Error' => $this->trustPay->errorUrl,
            'Notification' => $this->trustPay->notificationUrl,
        ];
    }
}