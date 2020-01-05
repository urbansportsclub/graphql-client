<?php

namespace OneFit\GraphQLClient;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

/**
 * Class GraphQLClientServiceProvider
 * @package OneFit\GraphQLClient
 */
class GraphQLClientServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/graphql.php', 'graphql');
        $this->app->bind(ApiClient::class, function () {
            $client = app()->make(Client::class);

            return new ApiClient($client,
                config('graphql.url')
            );
        });
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [ApiClient::class];
    }
}
