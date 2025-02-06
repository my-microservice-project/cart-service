<?php

return [
    'timeout' => env('SERVICE_TIMEOUT', 5),
    'headers' => [
        'Accept' => 'application/json',
    ],
    'base_path' => env('SERVICES_BASE_PATH', 'docker.for.mac.localhost'),
];
