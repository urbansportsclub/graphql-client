<?php

namespace OneFit\GraphQLClient;

use Illuminate\Support\Arr;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class GraphQLClient
 * @package OneFit\GraphQLClient
 */
abstract class GraphQLClient
{
    /**
     * HTTP request Clients.
     *
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * Request options.
     *
     * @var array
     */
    protected $options = [];

    /**
     * total timeout of the request in seconds
     * @var float
     */
    protected $timeout = 0;

    /**
     * number of seconds to wait while trying to connect to a server
     * @var float
     */
    protected $connectionTimeout = 0;

    /**
     * @return array
     */
    protected function getHeaders(): array
    {
        return  [
                'Accept' => 'application/json',
        ];
    }

    /**
     * Get the request Clients.
     *
     * @return ClientInterface
     */
    protected function getClient(): ClientInterface
    {
        return $this->httpClient;
    }

    /**
     * @param string $graphQlQuery
     * @param array $option
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendRequest(string $graphQlQuery, array $option = []): ResponseInterface
    {
        try {
            $payload = $this->preparePayload($graphQlQuery, $option);
            $response = $this->getClient()->request('POST', $this->getUrl(), $this->prepareRequestOptions($payload));
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            if ($exception->getCode() == Response::HTTP_BAD_REQUEST) {
                throw new BadRequestHttpException('There is problem with the payload sent in request. '.$exception->getMessage(),
                    $exception->getPrevious(),
                    $exception->getCode());
            }
            throw new HttpException(
                $exception->getCode(),
                $exception->getMessage(),
                $exception->getPrevious()
            );
        }

        return $response;
    }

    abstract public function getToken(): string;

    abstract public function getUrl(): string;


    /**
     * @param string $graphQlQuery
     * @param array $options
     * @return array
     */
    private function preparePayload(string $graphQlQuery, array $options = []): array
    {
        $payload = [
            'operationName' => '',
            'query' => $this->preparePagination($graphQlQuery, $options),
            'variables'=> Arr::get($options, 'variables', []),
        ];

        return [
            'json' => $payload,
        ];
    }

    /**
     * @param string $graphQlQuery
     * @param array  $options
     *
     * @return string
     */
    private function preparePagination(string $graphQlQuery, array $options): string
    {
        return str_replace('{cursor}', Arr::get($options, 'cursor', ''), $graphQlQuery);
    }

    private function authHeaders(): array
    {
        if($this->getToken()) {
            return [
              'Authorization' => 'Bearer ' . $this->getToken(),
            ];
        }

        return [];
    }

    /**
     * @param array $payload
     * @return array
     */
    private function prepareRequestOptions(array $payload): array
    {
        $headers = ['headers' => array_merge($this->getHeaders(), $this->authHeaders())];
        $timeout = ['timeout' => $this->timeout];
        $connectionTimeout = ['connect_timeout' => $this->connectionTimeout];

        return array_merge($headers, $timeout, $connectionTimeout, $payload);
    }
}
