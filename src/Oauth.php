<?php

namespace MarekVikartovsky\TrustPay;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class Oauth
{
    private const ACCESS_TOKEN_REQUEST_URL = 'https://aapi.trustpay.eu/api/oauth2/token';

    /**
     * Access token request response body.
     *
     * @var array
     */
    private array $accessTokenResponse = [];

    /**
     * @param TrustPay $trustPay
     */
    public function __construct(private readonly TrustPay $trustPay) {}

    /**
     * Returns access token.
     *
     * @return string|null
     * @throws ConnectionException
     */
    public function getAccessToken(): ?string
    {
        $response = Http::withBasicAuth($this->trustPay->projectID, $this->trustPay->secretKey)->withBody(http_build_query([
            'grant_type' => 'client_credentials',
        ]))->post(self::ACCESS_TOKEN_REQUEST_URL);

        $this->accessTokenResponse = $response->json();

        return $this->accessTokenResponse['access_token'] ?? null;
    }

    /**
     * Returns access token response.
     *
     * @return array
     */
    public function getAccessTokenResponse(): array
    {
        return $this->accessTokenResponse;
    }
}