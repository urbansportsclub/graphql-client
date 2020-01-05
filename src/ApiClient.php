<?php

namespace OneFit\GraphQLClient;

use GuzzleHttp\ClientInterface;

/**
 * Class ApiClient
 * @package OneFit\GraphQLClient
 */
class ApiClient extends GraphQLClient
{
    /** @var string */
    private $baseUrl;

    /** @var string */
    private $token;

    /**
     * ApiClient constructor.
     * @param ClientInterface $httpClient
     * @param string $baseUrl
     * @param string $token
     */
    public function __construct(ClientInterface $httpClient, string $baseUrl)
    {
        $this->httpClient = $httpClient;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token ?? '';
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->baseUrl;
    }
}
