<?php

namespace MarekVikartovsky\TrustPay\CallbackHandlers;

use Illuminate\Http\Request;
use MarekVikartovsky\TrustPay\Enums\TransactionNotificationStatusEnum;

class NotificationHandler
{
    public function __construct(
        private readonly string $secretKey,
        private readonly Request $request
    ) {}

    /**
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->getStatus() === TransactionNotificationStatusEnum::PAID->value;
    }

    /**
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->getStatus() === TransactionNotificationStatusEnum::REJECTED->value;
    }

    /**
     * @return bool
     */
    public function isChargeBacked(): bool
    {
        return $this->getStatus() === TransactionNotificationStatusEnum::CHARGE_BACKED->value;
    }

    /**
     * @return bool
     */
    public function isRapidDisputeResolution(): bool
    {
        return $this->getStatus() === TransactionNotificationStatusEnum::RAPID_DISPUTE_RESOLUTION->value;
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->getStatus() === TransactionNotificationStatusEnum::AUTHORIZED->value;
    }

    /**
     * Check of signature is valid.
     *
     * @return bool
     */
    public function hasValidSignature(): bool
    {
        return $this->getSignature() === $this->computeSignature();
    }

    /**
     * Payment method.
     *
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return $this->request->json('PaymentMethod', '');
    }

    /**
     * Payment status.
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->request->json('PaymentInformation.Status', '');
    }

    /**
     * Payment status.
     *
     * @return string|null
     */
    public function getStatusReasonCode(): ?string
    {
        return $this->request->json('PaymentInformation.StatusReasonInformation.Reason.Code');
    }

    /**
     * Payment status.
     *
     * @return string|null
     */
    public function getStatusRejectReason(): ?string
    {
        return $this->request->json('PaymentInformation.StatusReasonInformation.Reason.RejectReason');
    }

    /**
     * Payment amount.
     *
     * @return string|null
     */
    public function getAmount(): ?string
    {
        return number_format($this->request->json('PaymentInformation.Amount.Amount', 0), 2, '.', '');
    }

    /**
     * Payment currency.
     *
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->request->json('PaymentInformation.Amount.Currency');
    }

    /**
     * Payment ID.
     *
     * @return string|null
     */
    public function getPaymentId(): ?string
    {
        return $this->request->json('PaymentInformation.References.PaymentId');
    }

    /**
     * Payment request ID.
     *
     * @return string|null
     */
    public function getPaymentRequestId(): ?string
    {
        return $this->request->json('PaymentInformation.References.PaymentRequestId');
    }

    /**
     * Payment merchant reference.
     *
     * @return string|null
     */
    public function getMerchantReference(): ?string
    {
        return $this->request->json('PaymentInformation.References.MerchantReference');
    }

    /**
     * Payment ClearingSystemReference reference.
     *
     * @return string|null
     */
    public function getClearingSystemReferenceReference(): ?string
    {
        return $this->request->json('PaymentInformation.References.ClearingSystemReference');
    }

    /**
     * Payment OriginalPaymentRequestId reference.
     *
     * @return string|null
     */
    public function getOriginalPaymentRequestIdReference(): ?string
    {
        return $this->request->json('PaymentInformation.References.OriginalPaymentRequestId');
    }

    /**
     * Payment OriginalPaymentId reference.
     *
     * @return string|null
     */
    public function getOriginalPaymentIdReference(): ?string
    {
        return $this->request->json('PaymentInformation.References.OriginalPaymentId');
    }

    /**
     * Payment credit or debit indicator.
     *
     * @return string|null
     */
    public function getCreditDebitIndicator(): ?string
    {
        return $this->request->json('PaymentInformation.CreditDebitIndicator');
    }

    /**
     * Payment creditor name.
     *
     * @return string|null
     */
    public function getCreditorName(): ?string
    {
        return $this->request->json('PaymentInformation.Creditor.Name');
    }

    /**
     * Payment creditor iban.
     *
     * @return string|null
     */
    public function getCreditorIban(): ?string
    {
        return $this->request->json('PaymentInformation.CreditorAccount.Iban');
    }

    /**
     * Payment creditor agent bic.
     *
     * @return string|null
     */
    public function getCreditorAgentBic(): ?string
    {
        return $this->request->json('PaymentInformation.CreditorAgent.Bic');
    }

    /**
     * Payment debtor name.
     *
     * @return string|null
     */
    public function getDebtorName(): ?string
    {
        return $this->request->json('PaymentInformation.Debtor.Name');
    }

    /**
     * Payment debtor country code.
     *
     * @return string|null
     */
    public function getDebtorAddressCountryCode(): ?string
    {
        return $this->request->json('PaymentInformation.Debtor.Address.CountryCode');
    }

    /**
     * Payment debtor iban.
     *
     * @return string|null
     */
    public function getDebtorIban(): ?string
    {
        return $this->request->json('PaymentInformation.DebtorAccount.Iban');
    }

    /**
     * Payment debtor account other information.
     *
     * @return string|null
     */
    public function getDebtorAccountOther(): ?string
    {
        return $this->request->json('PaymentInformation.DebtorAccount.Other');
    }

    /**
     * Payment debtor agent bic.
     *
     * @return string|null
     */
    public function getDebtorAgentBic(): ?string
    {
        return $this->request->json('PaymentInformation.DebtorAgent.Bic');
    }

    /**
     * Payment card masked pan.
     *
     * @return string|null
     */
    public function getCardMaskedPan(): ?string
    {
        return $this->request->json('PaymentInformation.CardTransaction.Card.MaskedPan');
    }

    /**
     * Payment card expiry date.
     *
     * @return string|null
     */
    public function getCardExpiryDate(): ?string
    {
        return $this->request->json('PaymentInformation.CardTransaction.Card.ExpiryDate');
    }

    /**
     * Payment card token.
     *
     * @return string|null
     */
    public function getCardToken(): ?string
    {
        return $this->request->json('PaymentInformation.CardTransaction.Card.Token');
    }

    /**
     * Payment card subtype.
     *
     * @return string|null
     */
    public function getCardSubType(): ?string
    {
        return $this->request->json('PaymentInformation.CardTransaction.Card.SubType');
    }

    /**
     * Payment sep mandate UMR.
     *
     * @return string|null
     */
    public function getMandateInformationUMR(): ?string
    {
        return $this->request->json('PaymentInformation.SepaDirectDebitInformation.MandateInformation.UMR');
    }

    /**
     * Payment signature.
     *
     * @return string|null
     */
    public function getSignature(): ?string
    {
        return $this->request->json('Signature');
    }

    /**
     * Payment project ID.
     *
     * @return string|null
     */
    public function getProjectId(): ?string
    {
        return $this->request->json('MerchantIdentification.ProjectId');
    }

    /**
     * Computes signature from request data.
     *
     * @return string
     */
    private function computeSignature(): string
    {
        $fieldsToSign = array_filter([
            $this->getProjectId(),
            $this->getAmount(),
            $this->getCurrency(),
            $this->getMerchantReference(),
            $this->getStatus(),
            $this->getStatusReasonCode(),
            $this->getStatusRejectReason(),
            $this->getCreditDebitIndicator(),
            $this->getPaymentMethod(),
            $this->getCreditorName() ?? $this->getDebtorName(),
            $this->getDebtorAddressCountryCode(),
            $this->getCreditorIban() ?? $this->getDebtorIban(),
            $this->getCreditorAgentBic() ?? $this->getDebtorAgentBic(),
            $this->getMandateInformationUMR(),
            $this->getDebtorAccountOther(),
            $this->getPaymentRequestId(),
            $this->getPaymentId(),
            $this->getOriginalPaymentRequestIdReference(),
            $this->getOriginalPaymentIdReference(),
            $this->getClearingSystemReferenceReference(),
            $this->getCardMaskedPan(),
            $this->getCardExpiryDate(),
            $this->getCardToken(),
            $this->getCardSubType(),
        ]);
        sort($fieldsToSign, SORT_STRING);

        $message = implode('/', $fieldsToSign);

        return strtoupper(hash_hmac('sha256', pack('A*', $message), pack('A*', $this->secretKey)));
    }
}