<?php

namespace MarekVikartovsky\TrustPay;

use Illuminate\Http\Client\ConnectionException;
use MarekVikartovsky\TrustPay\Exceptions\TrustPayAccessTokenException;

class TrustPay
{
    /**
     * @param string $projectID
     * @param string $secretKey
     * @param string $notificationUrl
     * @param string $returnUrl
     * @param string $cancelUrl
     * @param string $errorUrl
     * @param string $language Default language for TrustPay site https://doc.trustpay.eu/#codes-lang
     */
    public function __construct(
        public string $projectID,
        public string $secretKey,
        public string $notificationUrl,
        public string $returnUrl,
        public string $cancelUrl = '',
        public string $errorUrl = '',
        public string $language = 'en',
    ) {}

    /**
     * Returns payment instance.
     *
     * @param string $method
     *
     * @return Payment
     * @throws TrustPayAccessTokenException
     * @throws ConnectionException
     */
    public function payment(string $method): Payment
    {
        $accessToken = (new Oauth($this))->getAccessToken();
        if (!$accessToken) {
            throw new TrustPayAccessTokenException('Access token not found');
        }

        return new Payment($this, $method, $accessToken);
    }
}