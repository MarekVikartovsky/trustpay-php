<?php

namespace MarekVikartovsky\TrustPay;

use Illuminate\Http\Client\ConnectionException;
use MarekVikartovsky\TrustPay\Enums\CurrencyEnum;
use MarekVikartovsky\TrustPay\Exceptions\TrustPayPaymentRequestException;
use MarekVikartovsky\TrustPay\PaymentMethods\CardPayment;
use MarekVikartovsky\TrustPay\PaymentMethods\Eps;
use MarekVikartovsky\TrustPay\PaymentMethods\Giropay;
use MarekVikartovsky\TrustPay\PaymentMethods\PaymentMethod;
use MarekVikartovsky\TrustPay\PaymentMethods\SepaCreditTransfer;
use MarekVikartovsky\TrustPay\PaymentMethods\Sofort;

class Payment
{
    /**
     * Amount of the payment (exactly 2 decimal places)
     *
     * @var string
     */
    private string $amount = '0.00';

    /**
     * Currency of the payment (same as currency of merchant account)
     *
     * @var CurrencyEnum
     * @see https://doc.trustpay.eu/#codes-cur
     */
    private CurrencyEnum $currency;

    /**
     * Reference (merchant’s payment identification)
     *
     * @var string
     */
    private string $reference = '';

    /**
     * Payment type.
     *
     * @var string|null
     */
    private ?string $paymentType;

    /**
     * Information that the payment gateway is not loaded inside an iframe
     *
     * @var bool
     */
    private bool $isRedirect = false;

    /**
     * @param TrustPay $trustPay
     * @param string $paymentMethod
     * @param string $accessToken
     */
    public function __construct(
        private readonly TrustPay $trustPay,
        private readonly string $paymentMethod,
        public string $accessToken,
    ) {}

    /**
     * Amount setter.
     *
     * @param float $amount
     *
     * @return $this
     */
    public function setAmount(float $amount): static
    {
        $this->amount = number_format($amount, 2, '.', '');

        return $this;
    }

    /**
     * Amount getter.
     *
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * Currency setter.
     *
     * @param CurrencyEnum $currency
     *
     * @return $this
     */
    public function setCurrency(CurrencyEnum $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Currency getter.
     *
     * @return CurrencyEnum
     */
    public function getCurrency(): CurrencyEnum
    {
        return $this->currency;
    }

    /**
     * Reference setter.
     *
     * @param string $reference
     *
     * @return $this
     */
    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Reference getter.
     *
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * Payment type setter.
     *
     * @param string $paymentType
     *
     * @return $this
     */
    public function setPaymentType(string $paymentType): static
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * Payment type getter.
     *
     * @return string
     */
    public function getPaymentType(): string
    {
        return $this->paymentType;
    }

    /**
     * Returns gateway url.
     *
     * @return string
     * @throws ConnectionException
     * @throws TrustPayPaymentRequestException
     */
    public function getPaymentUrl(): string
    {
        return $this->getPaymentMethodInstance()->handle();
    }

    /**
     * Set information that the payment gateway is not loaded inside an iframe.
     *
     * @return Payment
     */
    public function allowRedirect(): static
    {
        $this->isRedirect = true;

        return $this;
    }

    /**
     * Returns information if payment gateway is loaded inside an iframe.
     * TRUE = gateway is not loaded inside an iframe
     * FALSE = gateway is loaded inside an iframe
     *
     * @return bool
     */
    public function isRedirectAllowed(): bool
    {
        return $this->isRedirect;
    }

    /**
     * Returns payment method instance.
     *
     * @return PaymentMethod
     */
    private function getPaymentMethodInstance(): PaymentMethod
    {
        return match ($this->paymentMethod) {
            Giropay::PAYMENT_METHOD_NAME => new Giropay($this->trustPay, $this),
            Eps::PAYMENT_METHOD_NAME => new Eps($this->trustPay, $this),
            Sofort::PAYMENT_METHOD_NAME => new Sofort($this->trustPay, $this),
            SepaCreditTransfer::PAYMENT_METHOD_NAME => new SepaCreditTransfer($this->trustPay, $this),
            default => new CardPayment($this->trustPay, $this),
        };
    }
}