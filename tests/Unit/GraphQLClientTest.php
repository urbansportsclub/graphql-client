<?php

namespace OneFit\GraphQLClient\Tests;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use OneFit\GraphQLClient\ApiClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GraphQLClientTest extends TestCase
{

    /**
     * @var \Mockery\LegacyMockInterface|\Mockery\MockInterface|\OneFit\GraphQLClient\ApiClient
     */
    private $graphQLClient;

    /**
     * @var \GuzzleHttp\Handler\MockHandler
     */
    private $mock;

    protected function setUp(): void
    {
        $this->mock = new MockHandler([
          new Response(200, [], '{}'),
        ]);

        $handler = HandlerStack::create($this->mock);
        $client = new Client(['handler' => $handler]);
        $this->graphQLClient = \Mockery::mock(ApiClient::class,[$client,'url'])->makePartial();

        app()->bind(ApiClient::class, function ()   {
            return $this->graphQLClient;
        });

        parent::setUp();
    }

    /** @test */
    public function client_should_return_response_interface_object()
    {
        $client = app(ApiClient::class);
        $result = $client->sendRequest($this->fakeQuery(), $this->fakeOptions());

        $this->assertInstanceOf(Response::class, $result);
    }

    /** @test */
    public function should_throw_exception_for_bad_request()
    {
        $this->mock->reset();
        $this->mock->append(new BadRequestHttpException('bad request',null, 400));
        $client = app(ApiClient::class);
        $this->expectException(BadRequestHttpException::class);
        $client->sendRequest($this->fakeQuery(), $this->fakeOptions());
    }

    /** @test */
    public function should_throw_http_exception_for_other_fail_cases()
    {
        $this->mock->reset();
        $this->mock->append(new HttpException(403,'bad request',null));
        $client = app(ApiClient::class);
        $this->expectException(HttpException::class);
        $client->sendRequest($this->fakeQuery(), $this->fakeOptions());
    }

    /** @test */
    public function handle_guzzle_conenction_exception()
    {
        $this->mock->reset();
        $this->mock->append(new ConnectException('Timeout',
            new Request('get','/')));
        $this->expectException(ConnectException::class);
        $this->graphQLClient->sendRequest($this->fakeQuery(), $this->fakeOptions());
    }

    private function fakeQuery(): string
    {
        return '';
    }

    private function fakeOptions(): array
    {
        return [];
    }
}
