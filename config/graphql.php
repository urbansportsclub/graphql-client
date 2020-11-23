<?php

return [
  'graphql' => [
      'url' => env('GRAPHQL_url'),
      'connect_timeout' => env('GRAPHQL_CONNECTION_TIMEOUT'),
      'timeout' => env('GRAPHQL_TIMEOUT'),
  ],
];
