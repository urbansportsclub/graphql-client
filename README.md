# GraphQL Client
A simple bearer graphql client using guzzel for laravel or lumen projects.

## Install
``composer require onefit/graphql-client``

## Usage
The client currently support only the bearer token for authentication 

Define the base url in your .env file. 
```dotenv
GRAPHQL_URL=https://some-url
```

```php
//a simple mutation example
$client = app(ApiClient::class);
$response = $client->sendRequest('mutation someOperation(
                $instanceId: ID!
                $memberId: String!
            ) {
                someOperation (
                    instanceId: $instanceId,
                    input: {
                        memberId: $memberId
                    }
                ) 
                {
                    member {
                        id
                        firstName
                        lastName 
                        email
                        gender
                    }
                    status
                    updatedAt
                    createdAt
                }
            }', [
                'variables' => [
                    'instanceId' => $eventInstanceId,
                    'memberId' => (string) $memberId,
                ],
            ]);
        $responseObj = json_decode($response->getBody()->getContents(), true);

``` 