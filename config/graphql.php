<?php

return [
  'graphql' => [
      'url' => env('GRAPHQL_url'),
      'connect_timeout' => env('GRAPHQL_CONNECTION_TIMEOUT',0),
      'timeout' => env('GRAPHQL_REQUEST_TIMEOUT',0),
  ],
];

