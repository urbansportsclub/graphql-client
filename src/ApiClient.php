<?php

namespace OneFit\GraphQLClient;

use GuzzleHttp\ClientInterface;
use Illuminate\Support\Arr;

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
     * @param array $requestOptions
     */
    public function __construct(ClientInterface $httpClient, string $baseUrl, array $requestOptions = [])
    {
        $this->httpClient = $httpClient;
        $this->baseUrl = $baseUrl;
        $this->connectionTimeout = Arr::get($requestOptions,'connect_timeout', 0);
        $this->timeout = Arr::get($requestOptions,'timeout', 0);
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
